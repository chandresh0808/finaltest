from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText

import pymysql
import boto.ses
import boto.exception

import spotinstance
import logger
import config
import datetime


__author__ = 'Piyush'
__version__ = '0.0.1'

_log = logger.get_logger("analysisComplete")
_db = pymysql.connect(config.db_host, config.db_user, config.db_password, config.db_database)
_cursor = _db.cursor()


def main():
    """ Purpose : Fetch processed requests from auditcompanion mysql database and send completion mail,
        cancel spot instances request, terminate instance, change analysis request status"""
    # Open database connection
    try:
        _log.debug("Database connected")

        # Fetching data
        _cursor.execute("SELECT id, analysis_request_name, spot_instance_req_id, instance_id, user_id FROM analysis_request WHERE (status = 'Report Created') AND delete_flag = FALSE")
        requests = _cursor.fetchall()
        _log.debug("requests fetched")

        _log.info("Canceling spot instances request")
        for request in requests:
            try:
                _is_terminated = spotinstance.SpotInstance.cancel_spot_instance(str(request[2]), request[3])
                if _is_terminated:
                    _log.info("Instance for request {} terminated.".format(request[2]))
                _cursor.execute("UPDATE analysis_request SET status = 'Completed' WHERE id = {}".format(request[0]))
                _db.commit()
                
                now = datetime.datetime.now()
                dateTime = now.strftime("%Y-%m-%d %H:%M:%S")

                _cursor.execute("SELECT id FROM activity WHERE (type = 'Audit Analysis Complete') AND delete_flag = FALSE")
                activityId = _cursor.fetchall()

                _cursor.execute("INSERT INTO system_activity (`activity_id`, `user_id`, `comment`, `created_dt_tm`, `updated_dt_tm`, `delete_flag`) VALUES ('{}', '{}', '{}', '{}', '{}', '{}')".format(activityId[0][0],request[4],'Audit Analysis '+request[1]+' is completed',dateTime,dateTime,0))                 
                _db.commit()

                _log.info(
                    "Canceled SpotInstanceRequestID : {} and terminated InstanceID {} for Analysis request {}.".format(
                        request[2], request[3], request[1]))
                send_mail(request[0])
            except boto.exception.EC2ResponseError as e:
                _log.error("Error while canceling SpotInstanceRequestID {} and terminating instance {} of Analysis request {} : {}".format(request[2], request[3], request[1], e))
        # disconnect from database server
        _db.close()
        _log.debug("Database disconnected")
    except Exception as e:
        _log.error(str(e))


def send_mail(req_id):
    try:
        _log.debug("Database connected")
        # Fetching data
        _cursor.execute(
            "SELECT u.username, u.first_name, req.analysis_request_name FROM analysis_request AS req JOIN user AS u ON req.user_id = u.id WHERE req.id = {}".format(
                req_id))
        _req_info = _cursor.fetchone()

        conn = boto.ses.connect_to_region(config.aws_region,aws_access_key_id=config.aws_access_key,aws_secret_access_key=config.aws_secret_access_key)

        msg = MIMEMultipart()
        msg['Subject'] = "Analysis request completed"
        msg['From'] = config.from_mail_address
        msg['To'] = _req_info[0]
        msg.attach(MIMEText("Hi {},\nYour report for analysis request {} is completed.".format(_req_info[1], _req_info[2])))

        conn.send_raw_email(msg.as_string())
    except Exception as e:
        _log.error("Error while sending email : {}".format(e))


if __name__ == "__main__":
    main()
