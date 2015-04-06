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
    
    public function authenticateUser($encryptedString)
    {
        $authManagerService = $this->getAuthManagerService();
        $userManagerService = $this->getUserManagerService();
        $apiService = $this->getApiService();        
        
        $userCredentialArray = $apiService->decryptUserCredential($encryptedString);
        
        $userObject = $authManagerService->authenticateUser(
            $userCredentialArray['user_name'], $userCredentialArray['password']
        );
        
        if (is_object($userObject)) {                        
            $userId = $userObject->getId();
            $guid = $apiService->getGUID();                                               
            $inputDataArray['user_id'] = $userId;
            $inputDataArray['session_guid'] = $guid;
            $userSessionObject = $userManagerService->createUserSessionEntry($inputDataArray);            
            if(is_object($userSessionObject)) {
                $response['success'] = true;
                $response['session_guid'] = $guid;
                $response['user_id'] = $userId;
            } else {
                $response['success'] = false;
                $response['message'] = Constant::ERR_MSG_AUTH_NOT_ABLE_TO_CREATE_USERSESSION_ENTRY;
            }
                        
        } else {
            $response['success'] = false;
            $response['message'] = Constant::ERR_MSG_AUTH_UNAUTHORIZED;
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
        $userHasSaltObject = $userManagerService->createUserHasSaltEntry($inputArray);

        if (is_object($userHasSaltObject)) {
            $responseArray['success'] = true;
            $responseArray['ref_id'] = $userHasSaltObject->getId();
            $responseArray['salt'] = $userHasSaltObject->getSalt();
        } else {
            $responseArray['success'] = false;
            $responseArray['message'] = Constant::ERR_MSG_NOT_ABLE_TO_GENERATE_SALT;
        }
        
        return $responseArray;
    }
    
    /*
     * Get s3 bucket configuration
     * 
     * @return array $responseArray
     */
    
    public function getS3BacketConfig($sessionGuid) {
        
        $userManagerService = $this->getUserManagerService();
        
        //@TODO : Need to move this function call to on bootstrap 
        $result = $userManagerService->isUserHasSession($sessionGuid);        
        if (!$result) {
            $responseArray['success'] = false;
            $responseArray['message'] = Constant::ERR_MSG_AUTH_TOKEN_EXPIRED;
            return $responseArray;
        }
        
        $applicationEnv = getenv('APPLICATION_ENV');
        $s3BucketConfiguration = $this->getS3BucketConfiguration();
        $envSpecificS3BucketConfiguration = $s3BucketConfiguration[$applicationEnv];
        return $envSpecificS3BucketConfiguration;        
    }
    
}