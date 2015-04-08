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
    const USER_SESSION_GUID = 'session_guid';
    

    /* Authentication error message */  
    const ERR_MSG_AUTH_UNAUTHORIZED = 'Not a authorized user';
    const ERR_MSG_AUTH_NOT_ABLE_TO_CREATE_USERSESSION_ENTRY = 'Not able to create user session entry';
    const ERR_MSG_GENERAL = 'Whoops, looks like something went wrong';
    const ERR_MSG_NOT_ABLE_TO_GENERATE_SALT = 'Not able to generate salt';
    const ERR_MSG_AUTH_TOKEN_EXPIRED = 'Access token expired';
    
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
}