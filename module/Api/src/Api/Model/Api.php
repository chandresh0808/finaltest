<?php

/**
 * Define a Business class for CMS Api request
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
 * Define a Business class for CMS Api request
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
class Api extends \Application\Model\AbstractCommonServiceMutator
{
    /*
     * Guid generator
     * 
     * @return $uuid
     */

    function getGUID()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double) microtime() * 10000); //optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45); // "-"
            $uuid = //chr(123)// "{"
                    substr($charid, 0, 8) . $hyphen
                    . substr($charid, 8, 4) . $hyphen
                    . substr($charid, 12, 4) . $hyphen
                    . substr($charid, 16, 4) . $hyphen
                    . substr($charid, 20, 12);
            //. chr(125); // "}"
            return $uuid;
        }
    }

    /*
     * Decrypt the user credentials
     * 
     * @param string $encryptedString
     * 
     * @return array $responseArray
     * 
     */

    public function decryptUserCredential($encryptedString, $authSalt)
    {

        $decryptedString = $this->decryptData($encryptedString, $authSalt);        
        $decryptedString = $this->pkcs5Unpad($decryptedString);        
        $userCredentialArray = explode(Constant::USER_CREDENTIALS_SEPARATOR, $decryptedString, 2);
        $responseArray['user_name'] = trim($userCredentialArray[0]);
        $responseArray['password']  = trim($userCredentialArray[1]);

        return $responseArray;
    }

    /*
     * removing padding from decrypted string
     * @param string $decryptedString
     * 
     * @return string $decryptedString
     * 
     */
    function pkcs5Unpad($decryptedString)
    {
       $pad = ord($decryptedString{strlen($decryptedString)-1});
       if ($pad > strlen($decryptedString)) return false;
       return substr($decryptedString, 0, -1 * $pad);
    }

    
    /*
     * Decrypt the data
     * 
     * @param string $encryptedString
     * 
     * @return string $decryptedString
     */

    public function decryptData($encryptedString, $salt)
    {
        $decrypted = mcrypt_decrypt(
                MCRYPT_RIJNDAEL_128, $salt, base64_decode($encryptedString), MCRYPT_MODE_ECB);
        return $decrypted;
    }

    /*
     * Generate Salt for AES encryption and decryption of a string
     */
    function generateSalt($length = 0) {
        $charset = Constant::SALT_CHAR_SET;
        $str = '';
        $count = strlen($charset);
        while ($length-- > 0) {
            $str .= $charset[mt_rand(0, $count-1)];
        }
    return $str;
    }

    
    /*
     * Log API request
     */
    
    public function logApiRequest($e)
    {
        $logService = $e->getApplication()->getServiceManager()->get('log_service');
      
        $apiUri = $_SERVER['REDIRECT_URL'];
        
        $apiParamArray = $this->apiRequestParameterList($apiUri);   
        
        $message = "Request Api: " . $apiUri . " @@@@ Input Param: ";        
        foreach($apiParamArray as $k => $v) {             
            $tempArray = explode("_", $v, 2); 
            $param_key = strtolower($tempArray[1]);
            $param_value =  $_SERVER[$v];
            
            if ($param_value) {
                $message .= $param_key . " => " . $param_value . " &&& ";
            }
                                   
        }   
        $logService->debug($message);        
    }
       
    /*
     * return url paramter based on api uri
     * @param string $apiUri
     * 
     * @return array $responseArray
     * 
     */
    public function apiRequestParameterList ($apiUri) {
                
        $apiParamArray = array('HTTP_SESSION_GUID');
        $apiParamArray[Constant::URL_AUTH_API] = array('HTTP_CREDENTIALS', 'HTTP_REF_ID');            
        $responseArray = $apiParamArray[$apiUri];
        
        if (!count($responseArray)) {
            $responseArray = array('HTTP_SESSION_GUID');
        }
        return $responseArray;
    }
    
    /*
     * log API response
     */
    public function logApiResponse($e) {
              
        $logService = $e->getApplication()->getServiceManager()->get('log_service');  
        $apiUri = $_SERVER['REDIRECT_URL'];
        $responce = $e->getApplication()->getResponse()->getContent();
        $message = "Responce for Api: " . $apiUri . " @@@@ Output: " . print_r($responce, 1);          
        $logService->debug($message);        
    }
}
