import time

import boto.ec2
import boto.ec2.networkinterface

import logger
import config

__author__ = 'Piyush'
__version__ = '0.0.1'

_log = logger.get_logger("spotInstance")
_connection = boto.ec2.connect_to_region(config.aws_region,
                                         aws_access_key_id=config.aws_access_key,
                                         aws_secret_access_key=config.aws_secret_access_key)


class SpotInstance:
    """ Purpose : To create and cancel spot instance request"""
    @staticmethod
    def create_spot_instance(req_id):
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
        spot_request = _connection.request_spot_instances(price=str(SpotInstance.get_price()),
                                                          image_id=config.image_id,
                                                          count=config.number_of_instances,
                                                          user_data=user_data.encode('utf-8'),
                                                          security_group_ids=config.security_group_ids,
                                                          instance_type=config.instance_type,
                                                          placement=config.spot_request_region,
                                                          subnet_id=config.subnet_id,
                                                          dry_run=config.dry_run)
        spot_instance_status = None
        # Creating database connection
        import pymysql

        db = pymysql.connect(config.db_host, config.db_user, config.db_password, config.db_database)
        _log.debug("Database connected to check instance status")
        cursor = db.cursor()
        while True:
            spot_instance_id = spot_request[0]
            spot_requests = _connection.get_all_spot_instance_requests()
            for request in spot_requests:
                if request.id == spot_instance_id.id:
                    if spot_instance_status != request.status.code:
                        cursor.execute(
                            "UPDATE analysis_request SET webserver_spot_status = '{}', spot_instance_req_id = '{}' WHERE id = {}".format(
                                request.state, request.id, req_id))
                        db.commit()
                        _log.info(
                            "AnalysisRequestID: {}, SpotRequestID: {}, RequestState: {}, Status: {}, InstanceID: {}".format(
                                req_id, request.id, request.state, request.status.code, request.instance_id))
                        spot_instance_status = request.status.code
                        # Checking status update from spot instance
                        if request.state == 'active':
                            while True:
                                try:
                                    # TODO this should be update from spot instance (Have to remove from final script)
                                    # cursor.execute(
                                    #     "UPDATE analysis_request SET  source_spot_status ='active', status = 'Processed' WHERE id = {}".format(
                                    #         req_id))
                                    # db.commit()

                                    db2 = pymysql.connect(config.db_host, config.db_user, config.db_password, config.db_database)
                                    _log.debug("Database connected to check instance status")
                                    cursor_new = db2.cursor()
                                    cursor_new.execute(
                                        'SELECT source_spot_status FROM analysis_request WHERE id = {}'.format(req_id))
                                    analysis_request = cursor_new.fetchone()
                                    cursor_new.close()

                                    db2.close()
                                    if analysis_request[0] == 'active':
                                        cursor.execute(
                                            "UPDATE analysis_request SET webserver_spot_status ='active', instance_id = '{}' WHERE id = {}".format(
                                                request.instance_id, req_id))
                                        db.commit()
                                        return
                                except Exception as e:
                                    _log.error("Error while updating request status : {}".format(e))
                                    break
            else:
                if spot_instance_status == 'active':
                    db.close()
                    break
                time.sleep(5)

    @staticmethod
    def cancel_spot_instance(spot_instance_id, instance_id=None):
        requests_canceled = _connection.cancel_spot_instance_requests(spot_instance_id)
        if instance_id:
            instances_terminated = _connection.terminate_instances([instance_id])
            return (requests_canceled[0].id == spot_instance_id) & (instances_terminated[0].id == instance_id)
        return requests_canceled[0].id == spot_instance_id

    @staticmethod
    def get_price():
        price_list = _connection.get_spot_price_history(instance_type=config.instance_type,
                                                        product_description=config.product_desc,
                                                        availability_zone=config.spot_request_region,
                                                        dry_run=config.dry_run)
        # We are bidding five times of the highest price from last 90 days price history. (As used in old script)
        # Spot instance will be stopped if spot price goes higher than bid price.
        # In any case this will only stop the SPOT to be preserved longer!!"
        # But we will have to pay the same price as is determined at that time.
        price_list = sorted(price_list, key=lambda price: price.price)
        return price_list.pop().price * 5


def main():
    _log.info("SpotInstance main")
    # print(SpotInstance.get_price())
    # SpotInstance.get_sec_group()
    # SpotInstance.create_spot_instance("94")
    # SpotInstance.cancel_spot_instance("sir-02bbczda")

if __name__ == "__main__":
    main()
