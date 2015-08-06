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
    const SALT_AUDIT_REQUEST_TYPE = 'auditRequest';
    const USER_SESSION_GUID = 'sessionGuid';
    const INPUT_PARAM_RULE_BOOK_ID = 'ruleBookId';
    const INPUT_PARAM_EXTRACT_NAME = 'extractName';
    const INPUT_PARAM_EXTRACT_FILE_NAME = 'extractFileName';
    const INPUT_PARAM_REF_ID = 'refId';
    const INPUT_PARAM_JOB_ID = 'jobId';
    const COOKIE_TIME_OUT = 86400; //1 day
    const DEFAULT_QUANTITY = 1; //
    const USER_SESSION_EXPIRE_TIME = 600; //
    
    /* paypal constant */
    const PAYMENT_METHOD_SALT = '6uyUpz1fVGXFjQNSByHJ72eQ7vMdclW6';
    const PAYPAL_ACK_ERROR = 'ACK key not found in response.';
    const PAYPAL_CUSTOM_MSG = 'Please enter valid card holder name';
    
    const FLAG_FREE_TRAIL_SET = 1;
    const FLAG_FREE_TRAIL_UNSET = 0;
    const FLAG_ACTIVE_PACKAGE_SET = 1;
    const FLAG_ACTIVE_PACKAGE_UNSET = 0;
    
    /* Order status */
    const ORDER_STATUS_PENDING = 'Pending';

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
    const ENTITY_STATE = 'Application\Entity\States';
    const ENTITY_CART = 'Application\Entity\Cart';
    const ENTITY_PACKAGE = 'Application\Entity\Package';
    const ENTITY_PACKAGE_HAS_CREDITS = 'Application\Entity\PackageHasCredits';
    const ENTITY_ITEM = 'Application\Entity\Item';
    const ENTITY_ORDER = 'Application\Entity\Order';
    const ENTITY_ROLE = 'Application\Entity\Role';
    const ENTITY_USER_HAS_ROLE = 'Application\Entity\UserHasRole';
    const ENTITY_SYSTEM_PARAM = 'Application\Entity\SystemParam';
    const ENTITY_RULEBOOK = 'Application\Entity\Rulebook';
    const ENTITY_ANALYSIS_REQUEST = 'Application\Entity\AnalysisRequest';
    const ENTITY_RISK = 'Application\Entity\Risk';
    const ENTITY_RISK_HAS_JOB_FUNCTION = 'Application\Entity\RiskHasJobFunction';
    const ENTITY_RULEBOOK_HAS_RISK = 'Application\Entity\RulebookHasRisk';
    const ENTITY_JOB_FUNCTION = 'Application\Entity\JobFunction';
    const ENTITY_TRANSACTIONS = 'Application\Entity\Transactions';
    const ENTITY_JOB_FUNCTION_HAS_TRANSACTION = 'Application\Entity\JobFunctionHasTransaction';
    const ENTITY_RULE_BOOK_HAS_RISK = 'Application\Entity\RulebookHasRisk';
    const ENTITY_NOTIFICATION_LOG = 'Application\Entity\NotificationLog';
    const ENTITY_EXTRACTS = 'Application\Entity\Extracts';
    const ENTITY_ACTIVITY = 'Application\Entity\Activity';
    const ENTITY_SYSTEM_ACTIVITY = 'Application\Entity\SystemActivity';
    
    /* Rule Book */
    const MSG_NO_RECORD_FOUND = 'No Record Found';
    
    /* DB config */
    const SET_DELETE_FLAG = '1';
    
    /* Success message */
    const MSG_USER_SESSION_DELETED_SUCCESS = 'User session deleted successfully';
    const MSG_USER_SESSION_NOT_DELETED_SUCCESS = 'User session not deleted successfully';
    const MSG_ANALYSIS_REQUEST_SUCCESS = 'Analysis Request created successfully';
    const MSG_EXTRACTS_SUCCESS = 'Extract has been created successfully';
    
    
    /* api url */
    const URL_AUTH_API = '/api/v1/auth';
    
    /* Request Analysis status */    
    const ANALYSIS_REQUEST_PENDING_STATUS = 'Pending';
    const ANALYSIS_REQUEST_COMPLETE_STATUS = 'Completed';
    
    /* upload rulebook excel */
    const NUMBER_OF_EXCEL_SHEET_FOR_UPLOAD_RULEBOOK = 7;
    
    /*Rulebook sheet */
    const RULEBOOK_SHEET_NAME = 'Rulebook';
    const NUM_OF_COLUMN_IN_RULEBOOK_SHEET = 2;
    const FIRST_COLUMN_NAME_IN_RULEBOOK_SHEET = 'RuleBookID';
    const SECOND_COLUMN_NAME_IN_RULEBOOK_SHEET = 'RuleBookDescription';
    
    /*Risk sheet */
    const RISK_SHEET_NAME = 'Risks';
    const NUM_OF_COLUMN_IN_RISK_SHEET = 5;
    const FIRST_COLUMN_NAME_IN_RISK_SHEET = 'RiskID';
    const SECOND_COLUMN_NAME_IN_RISK_SHEET = 'SingleFunctionRisk';
    const THIRD_COLUMN_NAME_IN_RISK_SHEET = 'RiskCategory';
    const FOUR_COLUMN_NAME_IN_RISK_SHEET = 'RiskLevel';
    const FIVE_COLUMN_NAME_IN_RISK_SHEET = 'RiskDescription';
    
    /*Rulebook has risk sheet */
    const RULEBOOK_HAS_RISK_SHEET_NAME = 'RuleBookRisk';
    const NUM_OF_COLUMN_IN_RULEBOOK_HAS_RISK_SHEET = 2;
    const FIRST_COLUMN_NAME_IN_RULEBOOK_HAS_RISK_SHEET = 'RuleBookID';
    const SECOND_COLUMN_NAME_IN_RULEBOOK_HAS_RISK_SHEET = 'RiskID';
    
    /*function sheet */
    const FUNCTION_SHEET_NAME = 'Function';
    const NUM_OF_COLUMN_IN_FUNCTION_SHEET = 2;
    const FIRST_COLUMN_NAME_IN_FUNCTION_SHEET = 'FunctionID';
    const SECOND_COLUMN_NAME_IN_FUNCTION_SHEET = 'FunctionDescription';
    
    /*risk has function  sheet */
    const RISK_HAS_FUNCTION_SHEET_NAME = 'RiskFunction';
    const NUM_OF_COLUMN_IN_RISK_HAS_FUNCTION_SHEET = 2;
    const FIRST_COLUMN_NAME_IN_RISK_HAS_FUNCTION_SHEET = 'RiskID';
    const SECOND_COLUMN_NAME_IN_RISK_HAS_FUNCTION_SHEET = 'FunctionID';
    
    /*transactions sheet */
    const TRANSACTION_SHEET_NAME = 'Transactions';
    const NUM_OF_COLUMN_IN_TRANSACTION_SHEET = 2;
    const FIRST_COLUMN_NAME_IN_TRANSACTION_SHEET = 'TCode';
    const SECOND_COLUMN_NAME_IN_TRANSACTION_SHEET = 'TransactionDescription';
        
    /*function has transaction sheet */
    const FUNCTION_HAS_TRANSACTION_SHEET_NAME = 'FunctionsTransactions';
    const NUM_OF_COLUMN_IN_FUNCTION_HAS_TRANSACTION_SHEET = 2;
    const FIRST_COLUMN_NAME_IN_FUNCTION_HAS_TRANSACTION_SHEET = 'FunctionID';
    const SECOND_COLUMN_NAME_IN_FUNCTION_HAS_TRANSACTION_SHEET = 'TCode';
    
    const MAX_UPLOAD_RULE_BOOK_SIZE = 5242880; //5mb
    const MAX_PERSIST_VALUE = 500;
    
    
    /* System  param key's */
    const SP_AR_NOTEFY_FIRST = 'analysis_request_first_notification_in_seconds';
    const SP_AR_NOTEFY_SECOND = 'analysis_request_second_notification_in_seconds';
    const SP_AR_NOTIFY_THIRD = 'analysis_request_third_notification_in_seconds';
    const AR_MAIL_STATUS = 'Sent';
    
    /* Adjust analysis credits */
    const CUSTOM_PACKAGE_ID = 2;
    const CUSTOM_PACKAGE_DURATION = 30; //days
    const CUSTOM_PACKAGE_TYPE = 'custom';
    const CUSTOM_PACKAGE_MAX_POINT = 999;
    
    /* Activity log */
    
    const ACTIVITY_CODE_PAC = 'PAC';
    const ACTIVITY_CODE_RA = 'RA';
    const ACTIVITY_CODE_DA = 'DA';
    const ACTIVITY_CODE_ADU = 'ADU';
    const ACTIVITY_CODE_EDU = 'EDU';
    const ACTIVITY_CODE_ACR = 'ACR';
    const ACTIVITY_CODE_ADA = 'ADA';
    const ACTIVITY_CODE_EDA = 'EDA';
    const ACTIVITY_CODE_USE = 'USE';
}