<?php

/**
 * Define a interface between CMSApiController and other modules
 * 
 * PHP version 5
 * 
 * @category   Module
 * @package    Api
 * @subpackage Model
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2015 CoS
 * @license    http://www.costrategix.com Proprietary 
 * @version    GIT: 1.7
 * @link       http://www.costrategix.com 
 * 
 */

namespace Api\Model;

use Application\Model\Constant as Constant;

/**
 * Define a interface between CMSApiController and other modules
 * 
 * PHP version 5
 * 
 * @category   Module
 * @package    Api
 * @subpackage Model
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2015 CoS
 * @license    http://www.costrategix.com Proprietary 
 * @version    GIT: 1.7
 * @link       http://www.costrategix.com 
 * 
 */
class ApiManager extends \Application\Model\AbstractCommonServiceMutator
{
    /*
     * authenticate user
     * 
     * @return array $response
     */

    public function authenticateUser($encryptedString, $refId)
    {
        $authManagerService = $this->getAuthManagerService();
        $userManagerService = $this->getUserManagerService();
        $apiService = $this->getApiService();

        /* Get auth salt using ref id */
        $authSaltObject = $userManagerService->getSystemSaltUsingId($refId);
        $authSalt = $authSaltObject->getSalt();

        /* Get user credentials */
        $userCredentialArray = $apiService->decryptUserCredential($encryptedString, $authSalt);

        /* authenticate the user */
        $userObject = $authManagerService->authenticateUser(
                $userCredentialArray['user_name'], $userCredentialArray['password']
        );

        if (is_object($userObject)) {
            $userId = $userObject->getId();
            $guid = $apiService->getGUID();
            $inputDataArray['user_id'] = $userObject;
            $inputDataArray['session_guid'] = $guid;
            $userSessionObject = $userManagerService->createUserSessionEntry($inputDataArray);
            if (is_object($userSessionObject)) {
                $response['success'] = true;
                $response['session_guid'] = $guid;
                $response['user_id'] = $userId;
            } else {
                $response = $this->_getResponseArray(false, Constant::ERR_MSG_AUTH_NOT_ABLE_TO_CREATE_USERSESSION_ENTRY);
            }
        } else {
            $response = $this->_getResponseArray(false, Constant::ERR_MSG_AUTH_UNAUTHORIZED);
        }

        return $response;
    }

    /*
     * Generate salt for Authentication
     * 
     * @return array $responseArray
     */

    public function generateAndSaveSalt()
    {

        $apiService = $this->getApiService();
        $userManagerService = $this->getUserManagerService();

        $salt = $apiService->generateSalt(Constant::SALT_CHAR_LENGTH);
        $inputArray['salt'] = $salt;
        $inputArray['type'] = Constant::SALT_AUTH_TYPE;
        $authSaltObject = $userManagerService->createAuthSaltEntry($inputArray);

        if (is_object($authSaltObject)) {
            $responseArray['success'] = true;
            $responseArray['ref_id'] = $authSaltObject->getId();
            $responseArray['salt'] = $authSaltObject->getSalt();
        } else {
            $responseArray = $this->_getResponseArray(false, Constant::ERR_MSG_NOT_ABLE_TO_GENERATE_SALT);
        }

        return $responseArray;
    }

    /*
     * Get s3 bucket configuration
     * 
     * @return array $responseArray
     */

    public function getS3BacketConfig($sessionGuid)
    {

        $userManagerService = $this->getUserManagerService();

        //@TODO : Need to move this function call to on bootstrap 
        $result = $userManagerService->isUserHasSession($sessionGuid);
        if (!$result) {
            $responseArray = $this->_getResponseArray(false, Constant::ERR_MSG_AUTH_TOKEN_EXPIRED);
            return $responseArray;
        }

        $applicationEnv = getenv('APPLICATION_ENV');
        $s3BucketConfiguration = $this->getS3BucketConfiguration();
        $envSpecificS3BucketConfiguration = $s3BucketConfiguration[$applicationEnv];
        return $envSpecificS3BucketConfiguration;
    }

    /*
     * Get user rule book list
     * @param string session_guid
     * 
     * @return array  $userRuleBookList
     * 
     */

    public function getUserRuleBookList($sessionGuid)
    {

        $userManagerService = $this->getUserManagerService();
        $userHasSession = $userManagerService->isUserHasSession($sessionGuid);

        if (!is_object($userHasSession)) {
            $responseArray = $this->_getResponseArray(false, Constant::ERR_MSG_AUTH_TOKEN_EXPIRED);
            return $responseArray;
        }

        /* Gets Role book list from user sessoin object */
        $ruleBookObject = $userHasSession->getUser()->getRoleBookList();

        if (is_object($ruleBookObject)) {
            $serializedData = $this->convertObjectToArrayUsingJmsSerializer($ruleBookObject);
            if (empty($serializedData)) {
                $responseArray = $this->_getResponseArray(false, Constant::MSG_NO_RECORD_FOUND);
            } else {
                $responseArray['success'] = true;
                $responseArray['rule_book_list'] = $this->convertObjectToArrayUsingJmsSerializer($ruleBookObject);
            }
        } else {
            $responseArray = $this->_getResponseArray(false, Constant::MSG_NO_RECORD_FOUND);
        }

        return $responseArray;
    }

    /*
     * Returns no record found message
     */

    private function _getResponseArray($status, $message)
    {
        $responseArray['success'] = $status;
        $responseArray['message'] = $message;
        return $responseArray;
    }

    /*
     * logout session
     * @param string $sessionGuid
     * 
     * @return array $responseArray
     */

    public function deleteUserSession($sessionGuid)
    {
        $userManagerService = $this->getUserManagerService();
        $userHasSession = $userManagerService->isUserHasSession($sessionGuid);

        if (!is_object($userHasSession)) {
            return $this->_getResponseArray(false, Constant::ERR_MSG_AUTH_TOKEN_EXPIRED);
        }

        $userSessionObject = $userManagerService->deleteUserSession($userHasSession);

        if (is_object($userSessionObject)) {
            $responseArray = $this->_getResponseArray(true, Constant::MSG_USER_SESSION_DELETED_SUCCESS);
        } else {
            $responseArray = $this->_getResponseArray(false, Constant::MSG_USER_SESSION_NOT_DELETED_SUCCESS);
        }
        return $responseArray;
    }
    
    /*
     * Generate Password for extract
     * @param string $sessionGuid
     * 
     * @return string $responseArray
     */
    
    public function generatePasswordForExtract () {
        
        
        $apiService = $this->getApiService();
        $userManagerService = $this->getUserManagerService();
   
        $salt = $apiService->generateSalt(Constant::SALT_CHAR_LENGTH);
        $inputArray['salt'] = $salt;
        $inputArray['type'] = Constant::SALT_AUDIT_REQUEST_TYPE;
        $authSaltObject = $userManagerService->createAuthSaltEntry($inputArray);

        if (is_object($authSaltObject)) {
            $responseArray['success'] = true;
            $responseArray['ref_id'] = $authSaltObject->getId();
            $responseArray['salt'] = $authSaltObject->getSalt();
        } else {
            $responseArray = $this->_getResponseArray(false, Constant::ERR_MSG_NOT_ABLE_TO_GENERATE_SALT);
        }

        return $responseArray;
        
    }
    
    
    /*
     * @TODO: Need to make common function for generating salt now it is repeating
     * generate salt based on type
     * 
     */

    public function generateSalt () {
        
    }
    
    
    /*
     * Get user credits
     * 
     * @param string $sessionGuid
     */
    public function getUserCredits($sessionGuid) {
        
        $userManagerService = $this->getUserManagerService();
        $userHasSession = $userManagerService->isUserHasSession($sessionGuid);
        if (!is_object($userHasSession)) {
            return $this->_getResponseArray(false, Constant::ERR_MSG_AUTH_TOKEN_EXPIRED);
        }
                
         /* Gets user has package list from user sessoin object */
        $userHasPackageObjectList = $userHasSession->getUser()->getUserHasPackageList();
        
        $totalPoints = 0;
        $creditPoints = 0;
        foreach($userHasPackageObjectList as $userHasPackageObject) {
            $userCreditHistoryObject = $userHasPackageObject->getUserCreditHistory()->first();
            $totalPoints += $userCreditHistoryObject->getTotalCreditAnalysisPoints();
            $creditPoints += $userCreditHistoryObject->getCreditAnalysisPointsUsed();
        }
       
        $availablePoints = ($totalPoints - $creditPoints);
        $responseArray['available_credits'] = $availablePoints;
        return $responseArray;
    }
    
    /*
     * Save analysis request
     * @param string $sessionGuid
     * @param int $ruleBookId
     * @param string $extractName
     * @param string $extractFileName
     * @param int $refId
     */
    
    public function saveAnalysisRequest($sessionGuid, $ruleBookId, $extractName, $extractFileName, $refId)
    {
        
        $userManagerService = $this->getUserManagerService();
        $analyticsManagerService = $this->getAnalyticsManagerService();
        $userHasSession = $userManagerService->isUserHasSession($sessionGuid);
        if (!is_object($userHasSession)) {
            return $this->_getResponseArray(false, Constant::ERR_MSG_AUTH_TOKEN_EXPIRED);
        }
                
         
          /* Get analysis request salt using ref id */
        $authSaltObject = $userManagerService->getSystemSaltUsingId($refId);
        $analysisRequestSalt = $authSaltObject->getSalt();
                
        $userId = $userHasSession->getUser()->getId();        
        $inputArray['user_id'] = $userId;
        $inputArray['rule_book_id'] = $ruleBookId;
        $inputArray['extract_name'] = $extractName;
        $inputArray['extract_file_name'] = $extractFileName;
        $inputArray['status'] = Constant::ANALYSIS_REQUEST_PENDING_STATUS;
        $inputArray['system_salt'] = $refId;
        
        $analysisRequestObject = $analyticsManagerService->createAnalysisReqeustEntry($inputArray);
               
        if (is_object($analysisRequestObject)) {
           $responseArray = $this->_getResponseArray(true, Constant::MSG_ANALYSIS_REQUEST_SUCCESS);
        } else {
           $responseArray = $this->_getResponseArray(false, Constant::ERR_MSG_NOT_ABLE_TO_GENERATE_SALT);
        }

        return $responseArray;
        
    }

}
