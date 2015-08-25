#raju@ip-172-31-39-119:/mnt/ebs/www/auditcompanion-portal/analysis_scripts$ sudo  cat testspot.py
import boto.ec2
import logger
import config
import pymysql
import constants

__author__ = 'Piyush'
__version__ = '0.0.1'


_log = logger.get_logger("testspot")
_connection = boto.ec2.connect_to_region(config.aws_region,
                                         aws_access_key_id=config.aws_access_key,
                                         aws_secret_access_key=config.aws_secret_access_key)


db = pymysql.connect(config.db_host, config.db_user, config.db_password, config.db_database)
selectSQL = str('')
updateSQL = str('')
analreqdict = {}
dictline = {}
return_spot_id = str('')
job_sir_id = ''
key_id = ''


class testSpot:
    @staticmethod
    def get_price():
        price_list = _connection.get_spot_price_history(instance_type=config.instance_type,
                                                        product_description=config.product_desc,
                                                        availability_zone=config.spot_request_region,
                                                        dry_run=config.dry_run)
        # highest price in last 90 days * 5
        price_list = sorted(price_list, key=lambda price: price.price)
        return price_list.pop().price * 5


    def find_new_pending_anal_requests():
        for key_id in analreqdict:
            dictline =  analreqdict[key_id]
            req_id = dictline['id']
            status = dictline['status']
            createspotflag = 0
            job_sir_id = str(dictline['spot_instance_req_id'])
            _log.info("job sire id = {}".format(job_sir_id))
            if status == constants.ANALYSIS_STATUS_PENDING:
                if "sir-" in job_sir_id:
                    createspotflag = 0 
                else:
                    createspotflag = 1
            if status == constants.ANALYSIS_STATUS_FAILED:
                createspotflag = 1

            if createspotflag == 0:
                 action = 'SKIPPING'
            if createspotflag == 1:
                 action = 'CREATING'
                 #_log.info("job sir id = {} key_id {}".format(job_sir_id,key_id))
                 job_sir_id = testSpot.start_new_pending_anal_requests(req_id)
                 #_log.info("job sir id = {} key_id {}".format(job_sir_id,key_id))
        # then modify --
                 dictline['spot_instance_req_id'] = job_sir_id
                 dictline['status'] = constants.ANALYSIS_STATUS_PENDING
                 dictline['source_spot_status'] = constants.INSTANCE_STATUS_OPEN
                 dictline['webserver_spot_status'] = constants.SPOT_STATUS_OPEN
                 dictline['instance_id'] = ''
        # push it back into  buffers
                 analreqdict[key_id] = dictline
            _log.info("{} {}, {}".format(action,key_id, job_sir_id))
        
        #end of find_new_pending_anal_requests()


    def start_new_pending_anal_requests(req_id):
        user_data = "\n".join(['{',
                               '    "req_id" : {},'.format(req_id),
                               '    "database" : {',
                               '        "db_host" : "{}",'.format(config.db_host),
                               '        "db_user" : "{}",'.format(config.db_user),
                               '        "db_password" : "{}",'.format(config.db_password),
                               '        "db_database" : "{}"'.format(config.db_database),
                               '    },',
                               '    "s3_credentials" : {',
                               '        "aws_region" : "{}",'.format(config.aws_region),
                               '        "aws_access_key" : "{}",'.format(config.aws_access_key),
                               '        "aws_secret_access_key" : "{}"'.format(config.aws_secret_access_key),
                               '        "aws_bucket_name" : "{}",'.format(config.aws_bucket_name),
                               '    }',
                               '}'])
        _log.debug("User Data : {}".format(user_data)) 
    
        spot_request = _connection.request_spot_instances(price=str(testSpot.get_price()),
                                                          image_id=config.image_id,
                                                          count=config.number_of_instances,
                                                          user_data=user_data.encode('utf-8'),
                                                          security_group_ids=config.security_group_ids,
                                                          instance_type=config.instance_type,
                                                          placement=config.spot_request_region,
                                                          subnet_id=config.subnet_id,
                                                          dry_run=config.dry_run)
    


        job_sir_id = spot_request[0].id
        _log.info("job sir id = {} key_id {}".format(job_sir_id,key_id))
        return job_sir_id
     

    @staticmethod
    def name_tagging_all_spots_details():
        # get all spot instances data and compare the sir# to get the key_id
        # then update the dictionary
        sirs_id = ''
        sir_status_code = ''
        instance_id = ''
        source_web_status = ''
        all_spot_requests = _connection.get_all_spot_instance_requests()

    #processing all spot details 
        for sirs in all_spot_requests:
            sirs_id = sirs.id
            instance_id = sirs.instance_id
            sir_status_code = str(sirs.status.code)
            _log.info("-->>{},{},{}".format(sirs_id,sir_status_code,instance_id))        
           # we need a better flow management for canceled spots... 
           # increment the error counter for spot failure. The status will remain PENDING until 3 failures then it will FAILED thus allowing re-entry..   
           #anal_status_code = constants.ANALYSIS_STATUS_FAILED
            key_id = testSpot.find_key_for_sir(sirs_id)          
            if key_id == 'X':
                reqname = "(Stg)-unknown"
            else:
                dictline = analreqdict[key_id]
                reqname = dictline['analysis_request_name']
                dictline['webserver_spot_status'] = sir_status_code
                dictline['instance_id'] = instance_id
                # push it back into  buffers
                analreqdict[key_id] = dictline
                reqname = "(Stg)-" + str(key_id) + "-" + reqname 
                ## do the tags only if data is found in the dictionary
                sirs.add_tag(str(constants.TAG_INDEX_NAME),reqname)
                _log.info("tried to add SPOT Name tag {} ".format(reqname)) 

        # end-for
        

    @staticmethod
    def find_key_for_sir(sirs_id):
        return_key_id = 'X'
        for key_id in analreqdict:
            dictline = analreqdict[key_id]
            #_log.info("-->{}".format(dictline))
            if  dictline['spot_instance_req_id'] == sirs_id:
                return_key_id = key_id
        _log.info("-->{} spot key found for {} ".format(return_key_id,sirs_id))
        return return_key_id            


    @staticmethod
    def name_tagging_all_instances_details():
        # get all spot instances data and compare the sir# to get the key_id
        # then update the dictionary
        sir_status_code = ''
        instance_id = ''
        all_instances = _connection.get_all_instances()

    #processing all instance details
        for instances in all_instances:
            for inst in instances.instances:
                 instance_id = inst.id
                 if constants.TAG_INDEX_NAME in inst.tags:
                     _log.info("Instance with Name tag {}-->{}-->{}-->{}".format(
                              config.aws_region, inst.id, inst.state, 
                              inst.tags[constants.TAG_INDEX_NAME]))
                 else:
                     if inst.state == constants.INSTANCE_STATUS_RUNNING:
                         key_id = testSpot.find_key_for_instance(instance_id)
                         _log.info("Instance fit to tag {}-->{}-->{}".format(
                              config.aws_region, inst.id, inst.state))
                         if key_id == 'X':
                             reqname = "(Stg)-unknown"
                         else:
                             dictline = analreqdict[key_id]
                             reqname = dictline['analysis_request_name']
                             if dictline['webserver_spot_status'] == constants.SPOT_STATUS_FULFILLED:
                                 dictline['instance_id'] = instance_id
                                 reqname = "(Stg)-" + str(key_id) + "-" + reqname
                                 ## do the tags only if data is found in the dictionary
                                 #sirs.add_tag(str(constants.TAG_INDEX_NAME),reqname)
                                 inst.add_tag(str(constants.TAG_INDEX_NAME),reqname)
                                 _log.info("tried to add instance Name tag {} ".format(reqname))

        # end-for


    @staticmethod
    def find_key_for_instance(instance_id):
        return_key_id = 'X'
        for key_id in analreqdict:
            dictline = analreqdict[key_id]
            #_log.info("-->{}".format(dictline))
            if  dictline['instance_id'] == instance_id:
                return_key_id = key_id
        _log.info("-->{} instance key found for {} ".format(return_key_id,instance_id))
        return return_key_id



    @staticmethod
    def check_instances_active_on_AWS():
        litsystem = ''
        litinstance = ''        
        all_status = _connection.get_all_instance_status()
        _log.info("(all_status ---> {}".format(all_status))
        for status in all_status:
            activateinstance = 0
            litinstance= str(status.instance_status)
            litsystem = str(status.system_status)
            #
            if (litinstance == constants.STATUS_OK and
                litsystem == constants.STATUS_OK):
                _log.info("id={} .. is ready {}{}".format(status.id,status.instance_status,status.system_status))
                ok_instance_id = status.id
                activateinstance = 1
            else:
                _log.info("id={} .. is NOT ready to fire-up {}{}!!".format(status.id,status.instance_status,status.system_status))
            if activateinstance:
                testSpot.activate_instance_in_dictionary(status.id)


    @staticmethod
    def activate_instance_in_dictionary(status_id):
        # to read back the information
        for key_id in analreqdict:
            dictline =  analreqdict[key_id]
            if  dictline['instance_id'] == status_id:
        # then modify --
                dictline['source_spot_status'] = constants.INSTANCE_STATUS_ACTIVE
        # push it back into  buffers
                analreqdict[key_id] = dictline
        # now read it back to confirm
                dictline =  analreqdict[key_id]
                _log.info("Found to be ACTIVE {}, {} ".format(key_id,status_id))
   
    @staticmethod
    def read_from_DB_into_dictionary():
        cursor = db.cursor()
        # please know that this sql query will get very fancy as we evolve the logic
        selectSQL = "SELECT id,spot_instance_req_id,analysis_request_name,delete_flag,status,"
        selectSQL = selectSQL  + "spot_failure_count,instance_failure_count,source_spot_status,instance_id,webserver_spot_status" 
        selectSQL = selectSQL  + " FROM analysis_request "
        selectSQL = selectSQL  + " WHERE delete_flag = '0'  and status = '" + constants.ANALYSIS_STATUS_PENDING + "'"  
        selectSQL = selectSQL  + " and ( spot_failure_count < 3 or instance_failure_count < 3 )"
        selectSQL = selectSQL  + " "
                                   
        _log.info(selectSQL)

        cursor = db.cursor()
        count = cursor.execute(selectSQL)
        _log.info("Extracted {} line(s) into Dictionary ".format(count))
        
        analysis = cursor.fetchall()
        for rows in analysis:
            id = rows[0]
            spot_instance_req_id = rows[1]
            analysis_request_name = rows[2]
            delete_flag = rows[3]
            status = rows[4]
            spot_failure_count = rows[5]
            instance_failure_count = rows[6]
            source_spot_status = rows[7]
            instance_id = rows[8]
            webserver_spot_status = rows[9]
            if not webserver_spot_status: 
                webserver_spot_status = 'open'
            _log.info("{},{},{},{},{},{},{},{},{},{}".format(
                   id,spot_instance_req_id,analysis_request_name,delete_flag,status,spot_failure_count,
                   instance_failure_count,source_spot_status,instance_id,webserver_spot_status 
                                                       ))
        # to write information into dictionary
            analreqdict[id] = {'id': id,
                               'spot_instance_req_id': spot_instance_req_id, 
                               'analysis_request_name':analysis_request_name,
                               'delete_flag':delete_flag,
                               'status': status,
                               'spot_failure_count':spot_failure_count,
                               'instance_failure_count':instance_failure_count,
                               'source_spot_status':source_spot_status,
                               'instance_id':instance_id,
                               'webserver_spot_status':webserver_spot_status }



    @staticmethod
    def write_dictionary_into_DB():
        count = 0
        # to write back the information
        for key_id in analreqdict:
            #_log.info("key found {} with the information {}".format(key_id,analreqdict[key_id]))
            dictline =  analreqdict[key_id]
            updateSQL = str('')
            updateSQL = updateSQL + "UPDATE analysis_request SET " 
            updateSQL = updateSQL + "spot_instance_req_id = '" + dictline['spot_instance_req_id']  + "'," 
            updateSQL = updateSQL + "analysis_request_name = '" + dictline['analysis_request_name'] +"',"
            updateSQL = updateSQL + "delete_flag = '" + str(dictline['delete_flag'])  + "',"
            updateSQL = updateSQL + "status = '" + dictline['status']  + "',"
            updateSQL = updateSQL + "spot_failure_count = '" + str(dictline['spot_failure_count']) + "',"
            updateSQL = updateSQL + "instance_failure_count = '" + str(dictline['instance_failure_count']) + "',"
            updateSQL = updateSQL + "source_spot_status = '" + dictline['source_spot_status'] + "'," 
            updateSQL = updateSQL + "instance_id = '" + str(dictline['instance_id']) + "',"
            updateSQL = updateSQL + "webserver_spot_status = '" + dictline['webserver_spot_status'] + "'"
            updateSQL = updateSQL + " WHERE id = '" + str(dictline['id']) + "'" 
            #_log.info(" Query for updates:  {}".format(updateSQL)) 
            cursorwrite = db.cursor()
            count = count + cursorwrite.execute(updateSQL)
        _log.info("Records updated in the DB back from Dictionary = {}".format(count))
        db.commit()            



    @staticmethod
    def test_Update_dictionary_list():
        #read back the information
        _log.info("testing updating dictionary items")
        _log.info("read the existing record -- then modify the status then read it back")


        # to modify -- read it in buffers first 
        key_id = 141
        dictline =  analreqdict[key_id]
        _log.info(" B4 changing {},{},{},{},{},{},{},{}".format(
                   dictline['id'],
                   dictline['spot_instance_req_id'],
                   dictline['analysis_request_name'],
                   dictline['delete_flag'],
                   dictline['status'],
                   dictline['spot_failure_count'],
                   dictline['instance_failure_count'],
                   dictline['source_spot_status'] ))
        # then modify --  
        dictline['spot_instance_req_id'] = 'sir-999999'
        dictline['source_spot_status'] = 'open'
        # push it back into  buffers 
        analreqdict[key_id] = dictline
        # now read it back to confirm
        dictline =  analreqdict[key_id]
        _log.info("After changing {},{},{},{},{},{},{},{}".format(
                   dictline['id'],
                   dictline['spot_instance_req_id'],
                   dictline['analysis_request_name'],
                   dictline['delete_flag'],
                   dictline['status'],
                   dictline['spot_failure_count'],
                   dictline['instance_failure_count'],
                   dictline['source_spot_status']
                   ))


            
def main():


    #_log.info("testspot main")
    #
    testSpot.read_from_DB_into_dictionary()
    _log.info("<---DB read and loaded in the dictionary --->")
    #
    testSpot.name_tagging_all_spots_details()
    testSpot.name_tagging_all_instances_details()
    _log.info("<---information from AWS and Name tag done--->")
    #
    testSpot.check_instances_active_on_AWS()
    _log.info("<---active instances to be flagged to start from new SPOT done--->")
    #
    testSpot.find_new_pending_anal_requests()
    _log.info("<---new analysis SPOT requests done--->")
    #
    #testSpot.test_Update_dictionary_list()
    #
    testSpot.write_dictionary_into_DB()
    #
    _log.info("XXXXXXXXXXXXXXXXXXXX  end script  XXXXXXXXXXXXXXXXXXXXXXXXXXXX")


if __name__ == "__main__":
    main()


