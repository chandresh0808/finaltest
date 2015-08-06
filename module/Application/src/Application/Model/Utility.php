<?php

/**
 * Contains Commonly/reusable functions
 * 
 * 
 * PHP version 5
 * 
 * @category   Module
 * @package    Application
 * @subpackage Model
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2015 CoS
 * @license    http://www.costrategix.com Proprietary 
 * @version    GIT: 1.7
 * @link       http://www.costrategix.com 
 * 
 */

namespace Application\Model;

use Application\Model\Constant as Constant;

/**
 * Contains Commonly/reusable functions
 * 
 * 
 * PHP version 5
 * 
 * @category   Module
 * @package    Application
 * @subpackage Model
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2015 CoS
 * @license    http://www.costrategix.com Proprietary 
 * @version    GIT: 1.7
 * @link       http://www.costrategix.com 
 * 
 */
class Utility
{

    /**
     * Set data time fields for updation
     * 
     * @param Doctrine\ORM\Entity
     *
     * @return Doctrine\ORM\Entity
     */
    public function setDateTimeForUpdation($object)
    {
        $date = date_create(date('Y-m-d H:i:s'));
        $object->setUpdatedDtTm($date);
        return $object;
    }

    /**
     * Set default fields for creation
     * 
     * @param Doctrine\ORM\Entity
     *
     * @return Doctrine\ORM\Entity
     */
    public function setDateTimeForCreation($object)
    {
        $date = date_create(date('Y-m-d H:i:s'));
        $object->setUpdatedDtTm($date);
        $object->setCreatedDtTm($date);
        $object->setDeleteFlag(0);
        return $object;
    }

    /*
     *  return CurrentDateTime Object
     */

    public function getCurrentEpochTime()
    {
        $epochTime = time();
        return $epochTime;
    }

    /*
     * Encode the given string
     * @param string $str
     * @return string 
     */

    function urlBase64Encode($str)
    {
        return strtr(base64_encode($str), array('+' => '.', '=' => '-', '/' => '~'));
    }

    /*
     * Decode the given string
     * @param string $str
     * @return string 
     */

    function urlBase64Decode($str)
    {
        return base64_decode(strtr($str, array('.' => '+', '-' => '=', '~' => '/')));
    }

    /*
     *  generate random 3 digit number
     */

    function getRandom3DigitNumber()
    {
        return mt_rand(1, 999);
    }

    /*
     * @TODO: Need to remove same function from api.php (duplicate function)
     * Generate Salt for AES encryption and decryption of a string
     * 
     */

    function generateSalt($length = 0)
    {
        $charset = Constant::SALT_CHAR_SET;
        $str = '';
        $count = strlen($charset);
        while ($length-- > 0) {
            $str .= $charset[mt_rand(0, $count - 1)];
        }
        return $str;
    }

    /*
     * get ip address
     */

    public function getIdAddress()
    {
        $ipAddress = self::getMyIp();
        if (empty($ipAddress)) {
            $ipAddress = '127.0.0.1';
        }
        return $ipAddress;
    }
    /**
     * get ip address through function
    */
   function getMyIp() { 
       $configArray = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 
                          'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'
                      );
       
       foreach ($configArray as $key){
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
    
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }
    /**
     * set Cookie 
     *
     * @param \Zend\Mvc\MvcEvent $e
     *
     * @return boolean
     */
    public function setCustomCookie($e)
    {
        $timeout = Constant::COOKIE_TIME_OUT;
        $cookieValue = self::generateSalt(Constant::SALT_CHAR_LENGTH);
        $cookie = new \Zend\Http\Header\SetCookie('insert_cart', $cookieValue, time() + $timeout, "/");
        $e->getResponse()->getHeaders()->addHeader($cookie);
        return $cookieValue;
    }

    /**
     * set Cookie 
     *
     * @param \Zend\Mvc\MvcEvent $e
     *
     * @return boolean
     */
    public function deleteCustomCookie($e)
    {
        $timeout = Constant::COOKIE_TIME_OUT;
        $cookieValue = '';
        $cookie = new \Zend\Http\Header\SetCookie('insert_cart', $cookieValue, time() - $timeout, "/");
        $e->getResponse()->getHeaders()->addHeader($cookie);
        return true;
    }

    /*
     * Gets year list from current year to +10 years
     */

    public function getYearList()
    {
        $curYear = date('Y');
        for ($i = 0; $i <= 25; $i++) {
            $year = $curYear++;
            $yearArray[$year] = $year;
        }
        return $yearArray;
    }

    function getMonthList()
    {
        for ($i = 1; $i <= 12; $i++) {
            $monthNumber = str_pad($i, 2, 0, STR_PAD_LEFT);
            $monthName = date('F', mktime(0, 0, 0, $i + 1, 0, 0, 0));
            $monthArray[$monthNumber] = $monthNumber . ")  " .$monthName;
        }
        return $monthArray;
    }

    /*
     * Encrypt string
     * @param string $string
     * @param string $salt
     * 
     * @return string $encryptedString
     */

    function encrypt($string, $salt)
    {
        return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($salt), $string, MCRYPT_MODE_CBC, md5(md5($salt))));
    }

    /*
     * decrypt string
     * @param string $encryptedString
     * @param string $salt
     * 
     * @return string $decryptedString
     */

    function decrypt($encryptedString, $salt)
    {
        return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($salt), base64_decode($encryptedString), MCRYPT_MODE_CBC, md5(md5($salt))), "\0");
    }

    /*
     * get cart type using number
     */

    function getCardType($number)
    {
        $number = preg_replace('/[^\d]/', '', $number);
        if (preg_match('/^3[47][0-9]{13}$/', $number)) {
            return 'Amex';
        } elseif (preg_match('/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/', $number)) {
            return 'Diners Club';
        } elseif (preg_match('/^6(?:011|5[0-9][0-9])[0-9]{12}$/', $number)) {
            return 'Discover';
        } elseif (preg_match('/^(?:2131|1800|35\d{3})\d{11}$/', $number)) {
            return 'JCB';
        } elseif (preg_match('/^5[1-5][0-9]{14}$/', $number)) {
            return 'Mastercard';
        } elseif (preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/', $number)) {
            return 'Visa';
        } else {
            return 'Unknown';
        }
    }
    
    
    /* 
     * Check is emailId is valid
     * @param string $emailId
     * 
     * @return bool true/false
     */

    public function isEmailIdValid($emailId)
    {

        if (preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $emailId)) {
            return true;
        }
        return false;
    }

    /*
     * Checks wheather input contains character or not
     */
    
    public function hasAnyCharacter($inputString) {
        return preg_match('/[a-zA-Z]/', $inputString);
    }
    
    /**
     * Data table input format
     * 
     * @param array $paramArray  
     * 
     * @return array $requestParamArray
     * 
     */
    function customizeResultForDataTable($dataObject, $count)    {
        $responseDataArray['iTotalRecords'] =  $count;
        $responseDataArray['iTotalDisplayRecords'] = $count;          
        $responseDataArray['aaData'] = $dataObject; 
        return $responseDataArray;
    }
    
    /*
     * Returns no record found message
     */

    public function getResponseArray($status, $message)
    {
        $responseArray['status'] = $status;
        $responseArray['message'] = $message;
        return $responseArray;
    }
    
    /*
     * Case-insensitive string comparison
     */
    public function caseInsensitiveStringCompare($firstString, $secondString)
    {

        if (strcasecmp($firstString, $secondString) == 0) {
            return true;
        }
        return false;
    }
    
    /*
     * convert from UTC time to EST
     */
    public function convertUtcToEst($time) {        
        $UTC = new \DateTimeZone("UTC");
        $EST = new \DateTimeZone("America/New_York");
        $date = new \DateTime( $time, $UTC );
        $date->setTimezone( $EST );        
        return $date->format("Y-m-d H:i:s");
    }
    
    
}
