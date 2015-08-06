__author__ = 'piyush'

import logging


def get_logger(logger_name):
    logger = logging.getLogger(logger_name)
    logger.setLevel(logging.INFO)

    # create a file handler

    handler = logging.FileHandler('/var/log/auditcompanion/cron.log')
    handler.setLevel(logging.INFO)

    stream_handler = logging.StreamHandler()
    stream_handler.setLevel(logging.INFO)

    # create a logging format

    formatter = logging.Formatter('%(asctime)s - %(name)s - %(levelname)s - %(message)s')
    handler.setFormatter(formatter)
    stream_handler.setFormatter(formatter)

    # add the handlers to the logger

    logger.addHandler(handler)
    logger.addHandler(stream_handler)

    return logger