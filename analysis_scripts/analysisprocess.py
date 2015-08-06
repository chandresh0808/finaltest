import pymysql

import spotinstance
import logger
import config


__author__ = 'Piyush'
__version__ = '0.0.1'

_log = logger.get_logger("analysisProcess")


class CronJobRunningException(Exception):
    """ Cron job is running """
    pass


def main():
    """ Purpose : Fetch pending and failed analysis requests from audit companion database
        and create spot instances for each request"""
    try:
        # Open database connection
        db = pymysql.connect(host=config.db_host, user=config.db_user, password=config.db_password, db=config.db_database, port=3306)
        _log.debug("Database connected")

        # Checking script is running or not
        cursor = db.cursor()
        cursor.execute("SELECT param_value FROM system_param WHERE param_key = 'process_analysis_cron_flag'")
        flag = cursor.fetchone()
        if flag[0] == '1':
            raise CronJobRunningException("Cron job is running")

        # Setting cron job running flag 1
        cursor.execute("UPDATE system_param SET param_value = '1' WHERE param_key = 'process_analysis_cron_flag'")
        db.commit()

        # Fetching data
        cursor.execute("SELECT id, analysis_request_name, status FROM analysis_request WHERE (status = 'Pending' OR status = 'Failed') AND delete_flag = FALSE")
        requests = cursor.fetchall()
        _log.debug("requests fetched")

        _log.info("Started creating spot instances")
        for request in requests:
            _log.info("Creating spot instance request : {} status : {}.".format(request[1], request[2]))
            spotinstance.SpotInstance.create_spot_instance(str(request[0]))

        # Setting cron job running flag 0
        cursor.execute("UPDATE system_param SET param_value = '0' WHERE param_key = 'process_analysis_cron_flag'")
        db.commit()
    except KeyboardInterrupt as e:
        _log.error("Execution stopped manually setting cron job running flag to false.")
        # Setting cron job running flag 0
        cursor.execute("UPDATE system_param SET param_value = '0' WHERE param_key = 'process_analysis_cron_flag'")
        db.commit()
    except CronJobRunningException as e:
        _log.error("Cron job is running.")
    except Exception as e:
        _log.error("Error while creating spot instances {}".format(e))
        # Setting cron job running flag 0
        cursor.execute("UPDATE system_param SET param_value = '0' WHERE param_key = 'process_analysis_cron_flag'")
        db.commit()
    finally:
        # disconnect from server
        db.close()
        _log.debug("Database disconnected")

if __name__ == '__main__':
    main()