<?php

/**
 * DAO class
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

namespace User\Model;

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
class User extends \Application\Model\AbstractDao
{
    /*
     * User validation
     * @param array $inputArray
     * 
     * @return array $responseArray
     */

    public function validateUser($inputArray)
    {

        $result = array('valid' => true);
        $errorMessage = array();

        if (empty($inputArray['email_id'])) {
            $result['valid'] = false;
            $errorMessage['email'] = "Please enter email id";
        } else {

            $isEmailIdValid = $this->isEmailIdValid($inputArray['email_id']);
            if (!$isEmailIdValid) {
                $result['valid'] = false;
                $errorMessage['email'] = "Invalid emailid";
            } else {
                $emailUnique = $this->isEmailUnique($inputArray['email_id']);

                if ($emailUnique) {
                    $result['valid'] = false;
                    $errorMessage['email'] = "User account already exists.";
                }
            }
        }

        if (empty($inputArray['first_name'])) {
            $result['valid'] = false;
            $errorMessage['first_name'] = "Please enter first name";
        }

        if (empty($inputArray['last_name'])) {
            $result['valid'] = false;
            $errorMessage['last_name'] = "Please enter last name";
        }

        if (empty($inputArray['password'])) {
            $result['valid'] = false;
            $errorMessage['password'] = "Please enter password";
        } else {
            $isValidPassword = $this->isPasswordValid($inputArray['password']);

            if (!$isValidPassword) {
                $result['valid'] = false;
                $errorMessage['password'] = "Password doesn't match criteria";
            }
        }

        if (empty($inputArray['confirm_password'])) {
            $result['valid'] = false;
            $errorMessage['confirm_password'] = "Please enter confirm password";
        } else {

            $isPasswordAndConfirmPasswordSame = $this->isPasswordAndConfirmPasswordSame($inputArray['password'], $inputArray['confirm_password']);

            if (!$isPasswordAndConfirmPasswordSame) {
                $result['valid'] = false;
                $errorMessage['confirm_password'] = "Password doesn't match confirmation";
            }
        }

        if (empty($inputArray['terms_condition'])) {
            //$result['valid'] = false;
            //$errorMessage['terms_condition'] = "Please select Term and condition";
        }

        $result['error'] = $errorMessage;
        return $result;
    }
    
    
    /*
     * Associate User validation
     * @param array $inputArray
     * 
     * @return array $responseArray
     */
    
    public function validateAssociateUser($inputArray)
    {
        $result = array('valid' => true);
        $errorMessage = array();
        
        if (empty($inputArray['email_id'])) {
            $result['valid'] = false;
            $errorMessage['email'] = "Please enter email id";
        } else {

            $isEmailIdValid = $this->isEmailIdValid($inputArray['email_id']);
            if (!$isEmailIdValid) {
                $result['valid'] = false;
                $errorMessage['email'] = "Invalid emailid";
            } else {
                $emailUnique = $this->isEmailUnique($inputArray['email_id']);

                if ($emailUnique) {
                    $result['valid'] = false;
                    $errorMessage['email'] = "User account already exists.";
                }
            }
        }
        
        if (empty($inputArray['first_name'])) {
            $result['valid'] = false;
            $errorMessage['first_name'] = "Please enter first name";
        }

        if (empty($inputArray['last_name'])) {
            $result['valid'] = false;
            $errorMessage['last_name'] = "Please enter last name";
        }
        
        $result['error'] = $errorMessage;
        return $result;
    }
    
    
    
    
    /*
     * Populates user signup form
     * @param object $signUpForm
     * @param array $postDataArray
     * 
     * @return object $signUpForm
     */

    public function populateSignUpForm($signUpForm, $postDataArray)
    {

        if (isset($postDataArray["email"])) {
            $signUpForm->get('email')->setValue($postDataArray["email"]);
        }
        if (isset($postDataArray["first_name"])) {
            $signUpForm->get('first_name')->setValue($postDataArray["first_name"]);
        }
        if (isset($postDataArray["last_name"])) {
            $signUpForm->get('last_name')->setValue($postDataArray["last_name"]);
        }

        return $signUpForm;
    }

    /*
     * check is email unique
     * @param string $emailId
     * 
     * @return bool true/false
     * 
     */

    public function isEmailUnique($emailId)
    {

        $queryParameterArray['username'] = $emailId;
        $entity = Constant::ENTITY_USER;
        $userObject = $this->getEntityByParameterList($queryParameterArray, $entity);
        if (is_object($userObject)) {
            return true;
        }
        return false;
    }

    /* @TODO: This function has been moved utility calss, need to remove from here
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
     * Check is password is valid
     * @param string $password
     * 
     * @return bool true/false
     */

    /*
      Explaining $\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$
      $ = beginning of string
      \S* = any set of characters
      (?=\S{8,}) = of at least length 8
      (?=\S*[a-z]) = containing at least one lowercase letter
      (?=\S*[A-Z]) = and at least one uppercase letter
      (?=\S*[\d]) = and at least one number
      (?=\S*[\W]) = and at least a special character (non-word characters)
      $ = end of the string

     */

    public function isPasswordValid($password)
    {

        if (preg_match('$\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$', $password)) {
            return true;
        }
        return false;
    }

    /*
     * Check is password and confirm password is same
     *  
     * @param string $password
     * @param string $confirmPassword
     * 
     * @return bool true/false
     */

    public function isPasswordAndConfirmPasswordSame($password, $confirmPassword)
    {

        if (strcmp($password, $confirmPassword) == 0) {
            return true;
        }
        return false;
    }

    /*
     * validate reset password
     * 
     * @param array $postData
     * 
     * @return array $responseArray
     * 
     */

    public function validateResetForgotPasswordData($inputArray)
    {
        $result = array('valid' => true);
        $errorMessage = array();

        if (empty($inputArray['new_password'])) {
            $result['valid'] = false;
            $errorMessage['password'] = "Please enter password";
        } else {
            $isValidPassword = $this->isPasswordValid($inputArray['new_password']);

            if (!$isValidPassword) {
                $result['valid'] = false;
                $errorMessage['new_password'] = "Password doesn't match criteria";
            }
        }

        if (empty($inputArray['confirm_new_password'])) {
            $result['valid'] = false;
            $errorMessage['confirm_new_password'] = "Please enter confirm password";
        } else {

            $isPasswordAndConfirmPasswordSame = $this->isPasswordAndConfirmPasswordSame($inputArray['new_password'], $inputArray['confirm_new_password']);

            if (!$isPasswordAndConfirmPasswordSame) {
                $result['valid'] = false;
                $errorMessage['confirm_new_password'] = "Password doesn't match confirmation";
            }
        }

        $result['error'] = $errorMessage;
        return $result;
    }

    /*
     * Validate user accout details
     */

    public function validateUserAccountData($inputArray, $userObject)
    {

        $result = array('valid' => true);
        $errorMessage = array();


        if (empty($inputArray['first_name'])) {
            $result['valid'] = false;
            $errorMessage['first_name'] = "Please enter first name";
        }

        if (empty($inputArray['last_name'])) {
            $result['valid'] = false;
            $errorMessage['last_name'] = "Please enter last name";
        }

        
        if (!empty($inputArray['old_password'])) {
           $isValidCurrentPassword = $this->isCurrentPasswordValid($inputArray['old_password'], $userObject);

            if (!$isValidCurrentPassword) {
                $result['valid'] = false;
                $errorMessage['old_password'] = "Current password is not valid";
            }
        }
        
        if (!empty($inputArray['password'])) {
            $isValidPassword = $this->isPasswordValid($inputArray['password']);

            if (!$isValidPassword) {
                $result['valid'] = false;
                $errorMessage['password'] = "Password doesn't match criteria";
            }
        } 

        if (!empty($inputArray['confirm_password'])) {
           $isPasswordAndConfirmPasswordSame = $this->isPasswordAndConfirmPasswordSame($inputArray['password'], $inputArray['confirm_password']);

            if (!$isPasswordAndConfirmPasswordSame) {
                $result['valid'] = false;
                $errorMessage['confirm_password'] = "Password doesn't match confirmation";
            }
            
        } 

        /* Making old password mandatory if user enters password/confirm password */
        if((!empty($inputArray['password'])) || !empty($inputArray['confirm_password'])) {            
            if (empty($inputArray['old_password'])) { 
                $result['valid'] = false;
                $errorMessage['old_password'] = "Current password is not valid";
            }
        }
        
        $result['error'] = $errorMessage;
        return $result;
    }

    
    /*
     * check is current entered password is valid password
     */
    
    public function isCurrentPasswordValid($currentPassword, $userObject) {
               
        if (strcmp(md5($currentPassword), $userObject->getPassword()) == 0) {
            return true;
        }
        return false;
        
    }
}
