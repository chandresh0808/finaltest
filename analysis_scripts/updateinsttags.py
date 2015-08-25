#sudo  /usr/bin/python3 /mnt/ebs/www/auditcompanion-portal/analysis_scripts/updateinsttagsV2.py

import boto.ec2
import logger
import config
import pymysql
import constants


__author__ = 'Raju'
__version__ = '0.0.1'

_log = logger.get_logger("updateInstTagsV2")
conn = boto.ec2.connect_to_region(config.aws_region,
                                         aws_access_key_id=config.aws_access_key,
                                         aws_secret_access_key=config.aws_secret_access_key)



def main():

    connections = conn.get_all_instances()
    for connects in connections:
        for inst in connects.instances:
            if constants.TAG_INDEX_NAME in inst.tags:
                _log.info(
                "{}-->{}-->{}-->{}".format(
                config.aws_region, inst.id, inst.state, inst.tags[constants.TAG_INDEX_NAME])) 
            else:
                _log.info(
                "{}-->{}-->{}-->did not find Taginx**{}** ".format(
                config.aws_region, inst.id, inst.state, constants.TAG_INDEX_NAME))
                if inst.state == constants.INSTANCE_STATUS_RUNNING:
                    _log.info("found Unnamed id without NAME tag= {}".format(inst.id))
                    try:
                        # Open database connection 
                        db = pymysql.connect(host=config.db_host, user=config.db_user, password=config.db_password, db=config.db_database, port=3306)
                        _log.debug("Database connected")
                        # Checking script is running or not
                        selectstring="SELECT analysis_request_name FROM analysis_request WHERE instance_id = '" + inst.id + "'"
                        _log.info("Select statement = {}".format(selectstring))
                        cursor = db.cursor()
                        # Fetching data
                        count = cursor.execute(selectstring)
                        _log.info(".the number of rcords found = {}".format(count))
                        reqname = cursor.fetchall()
                        cursor_empty = 1
                        if webserver_spot_status == constants.SPOT_STATUS_FULFILLED
                            for names in reqname:
                                cursor_empty = 0
                                selectstring = "(Stg)-" + str(names[0])
                                _log.info("The name of request found was |*{}*|".format(selectstring))  
                                inst.add_tag(str(constants.TAG_INDEX_NAME),selectstring)
                        
                            if cursor_empty == 1:
                                selectstring = "(Stg)-UNKNOWN" 
                                _log.info("The name of request found was |*{}*|".format(selectstring))
                                inst.add_tag(str(constants.TAG_INDEX_NAME),selectstring)
                    finally:
                        # disconnect from server
                        db.close()
                        _log.debug("Database disconnected") 


if __name__ == '__main__':
    main()
