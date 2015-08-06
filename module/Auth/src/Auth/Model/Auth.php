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

namespace Auth\Model;
 
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

class Auth extends \Application\Model\AbstractDao
{
        
    
    /*
     * User Login Data
     * @param array $inputArray
     * 
     * @return array $result
     */

    public function validateLoginData($inputArray)
    {        
        $result = array('valid' => true);
        $errorMessage = array();

        if (empty($inputArray['email'])) {
            $result['valid'] = false;
            $errorMessage['email'] = "Please enter email address";
        } 

        if (empty($inputArray['password'])) {
            $result['valid'] = false;
            $errorMessage['password'] = "Please enter password";
        } 

        $result['error'] = $errorMessage;
        return $result;
    }
    
     /*
     * User Login Data
     * @param array $inputArray
     * 
     * @return array $result
     */

    public function validateForgotPasswordData($inputArray)
    {        
        $result = array('valid' => true);
        $errorMessage = array();

        if (empty($inputArray['email'])) {
            $result['valid'] = false;
            $errorMessage['email'] = "Please enter email address";
        } 

        $result['error'] = $errorMessage;
        return $result;
    }
    
    
}
