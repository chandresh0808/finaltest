<?php
/**
* Constants.php
* 
* Holds application constants
*
* @category   Application
* @package    Model
* @author     Costrategix <info@costrategix.com>
* @license    http://www.costrategix.com
*/
namespace Application\Model;

class Constant
{   
    /* Configurations UserCredential */
    const USER_CREDENTIALS_SEPARATOR = '|';
    const SALT_CHAR_SET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    const SALT_CHAR_LENGTH = 32;
    const SALT_AUTH_TYPE = 'auth';
    const SALT_AUDIT_REQUEST_TYPE = 'audit_request';
    const USER_SESSION_GUID = 'session_guid';
    const INPUT_PARAM_RULE_BOOK_ID = 'rule_book_id';
    const INPUT_PARAM_EXTRACT_NAME = 'extract_name';
    const INPUT_PARAM_EXTRACT_FILE_NAME = 'extract_file_name';
    const INPUT_PARAM_REF_ID = 'ref_id';

    /* Authentication error message */  
    const ERR_MSG_AUTH_UNAUTHORIZED = 'Not a authorized user';
    const ERR_MSG_AUTH_NOT_ABLE_TO_CREATE_USERSESSION_ENTRY = 'Not able to create user session entry';
    const ERR_MSG_GENERAL = 'Whoops, looks like something went wrong';
    const ERR_MSG_NOT_ABLE_TO_GENERATE_SALT = 'Not able to generate salt';
    const ERR_MSG_AUTH_TOKEN_EXPIRED = 'Access token expired';
    const ERR_MSG_ANALYSIS_REQUEST_FAIL = 'Not able to create Analysis Request';
    
    /* Entities */    
    const ENTITY_USER_SESSION = 'Application\Entity\UserSession';
    const ENTITY_USER = 'Application\Entity\User';
    const ENTITY_SYSTEM_SALT = 'Application\Entity\SystemSalt';
    
    /* Rule Book */
    const MSG_NO_RECORD_FOUND = 'No Record Found';
    
    /* DB config */
    const SET_DELETE_FLAG = '1';
    
    /* Success message */
    const MSG_USER_SESSION_DELETED_SUCCESS = 'User session deleted successfully';
    const MSG_USER_SESSION_NOT_DELETED_SUCCESS = 'User session not deleted successfully';
    const MSG_ANALYSIS_REQUEST_SUCCESS = 'Analysis Request created successfully';
    
    
    /* api url */
    const URL_AUTH_API = '/api/v1/auth';
    
    /* Request Analysis status */
    
    const ANALYSIS_REQUEST_PENDING_STATUS = 'Pending';
    
}