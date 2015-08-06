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

use Application\Model\Constant as Constant;
use Application\Model\Utility as Utility;
use Zend\Session\Container;

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
class AuthManager extends \Application\Model\AbstractCommonServiceMutator
{

    public function authenticateUser($userName, $password)
    {
        $authDaoService = $this->getAuthDaoService();
        $queryParameterArray['username'] = $userName;
        $queryParameterArray['password'] = md5($password);
        $queryParameterArray['activeFlag'] = 1;
        $queryParameterArray['isBlocked'] = 0;

        $entity = Constant::ENTITY_USER;

        $user = $authDaoService->getEntityByParameterList($queryParameterArray, $entity);
        if (is_object($user)) {
            $user->setLastLogin(new \DateTime("now"));
            $this->save($user);
            return $user;
        }
        return false;
    }

    /**
     * Authenticate function
     * writes to session if sucess and returns true else false
     *
     * @param List $dataList Input data list
     *
     * @return boolean result
     * */
    public function authenticate($dataList, $signInForm)
    {
        try {
            $url = $_SERVER['REQUEST_URI'];
            $authenticationService = $this->getAuthenticationService();
            $authService = $this->getAuthService();

            $responseArray = $authService->validateLoginData($dataList);

            if (false == $responseArray['valid']) {
                $response['success'] = false;
                $response['error_message'] = $responseArray['error'];

                if (isset($dataList["email"])) {
                    $signInForm->get('email')->setValue($dataList["email"]);
                }

                $response['sign_in_form'] = $signInForm;
            } else {
                $userManagerService = $this->getUserManagerService();

                $result = $authenticationService->authenticate($dataList);
                if ($result->getCode() > 0) {
                    $userObject = $result->getIdentity();
                    $userId = $userObject->getId();
                    $roleObject = $userManagerService->getRoleByUserId($userId);
                    
                    if (0 == $userObject->getActiveFlag() || 1 == $userObject->getDeleteFlag() || 1 == $userObject->getIsBlocked() || ($url == '/sign-in' && $roleObject->getName() == 'admin') || ($url == '/admin' && $roleObject->getName() != 'admin') ) {                        
                        $authenticationService->clearIdentity();
                        $response['success'] = false;
                        if (1 == $userObject->getIsBlocked()) {
                            $response['blocked'] = 'The provide user is blocked please contact support@auditcompanion.biz.';
                        } elseif (1 == $userObject->getDeleteFlag()) {
                            $response['deleted'] = 'The provide user is Deleted please contact support@auditcompanion.biz.';
                        }
                        return $response;
                    }                   
                    
                    $roleSession = new Container('role');
                    if (is_object($roleObject)) {
                        $roleSession->roleId = $roleObject->getId();
                        $roleSession->roleName = $roleObject->getName();
                    } else {
                        $roleSession->roleId = 2;
                        $roleSession->roleName = 'user';
                    }
                    $userObject->setLastLogin(new \DateTime("now"));
                    $this->save($userObject);
                    $authenticationService->getStorage()->write($userObject);
                    $response['success'] = true;
                } else {
                    $response['success'] = false;
                }
            }

            return $response;
        } catch (\Exception $exc) {
            throw new \Exception($exc);
        }
    }

    /*
     * Generate and sends password verification code to user email
     * 
     */

    public function sendForgotPasswordVerificationCode($paramList, $forgotPasswordForm)
    {
        $userManagerService = $this->getUserManagerService();
        $authService = $this->getAuthService();

        $systemParamService = $this->getSystemParamService();
        $key = 'Support';
        $fromEmail = '';
        $fromEmail = $systemParamService->getSystemParamValueByKey($key);

        $responseArray = $authService->validateForgotPasswordData($paramList);

        if (false == $responseArray['valid']) {
            $response['success'] = false;
            $response['error_message'] = $responseArray['error'];

            if (isset($paramList["email"])) {
                $forgotPasswordForm->get('email')->setValue($paramList["email"]);
            }

            $response['forgot_password_form'] = $forgotPasswordForm;
        } else {
            $inputArray['username'] = $paramList['email'];
            $userObject = $userManagerService->getUserByParameterList($inputArray);
            if (is_object($userObject)) {

                if ($userObject->getActiveFlag() == 0 OR $userObject->getIsBlocked() == 1) {
                    $response['success'] = false;
                    $response['error_message'] = "Sorry, the email address you entered does not exist or is not activated in the system.";
                    return $response;
                }

                $verificatioinCode = \Application\Model\Utility::generateSalt(Constant::SALT_CHAR_LENGTH);
                $userObject->setActivationCode($verificatioinCode);
                $userObject = $this->save($userObject);
                if (is_object($userObject)) {
                    $template = $this->getMailTemplateService()->getForgotPasswordTemplate($userObject, $verificatioinCode);
                    $email = $userObject->getUsername();
                    $this->getMailService()->sendMail($template, $email, $fromEmail);
                    $response['success'] = true;
                } else {
                    $response['success'] = false;
                    $response['error_message'] = "Not able to generate verification code";
                }
            } else {
                $response['success'] = false;
                $response['error_message'] = "Sorry, the email address you entered does not exist or is not activated in the system.";
            }
        }

        return $response;
    }

    /*
     * Reset forgot password
     */

    public function resetForgotPasswordVerificationCode($postData)
    {
        $userManagerService = $this->getUserManagerService();

        $responseArray = $userManagerService->validateResetForgotPasswordData($postData);

        if (false == $responseArray['valid']) {
            $response['success'] = false;
            $response['error_message'] = $responseArray['error'];
        } else {
            $inputArray['activationCode'] = $postData['verification_code'];
            $userObject = $userManagerService->getUserByParameterList($inputArray);

            if (is_object($userObject)) {
                $userObject->setActivationCode(Null);
                $userObject->setActiveFlag(1);
                $userObject->setPassword(md5($postData['new_password']));
                $userObject = $this->save($userObject);
                if (is_object($userObject)) {
                    $response['success_message'] = "Password updated successfully ";
                    $response['success'] = true;
                } else {
                    $response['success'] = false;
                    $response['error_message'] = array("Password not updated successfully");
                }
            } else {
                $response['success'] = false;
                $response['error_message'] = array("Invalid verification code");
            }
        }

        return $response;
    }

    /*
     * update user_id for cart using cookie_key
     */

    public function updateCartWithUserIdUsingCookie($cookieValue, $userId)
    {
        $authDaoService = $this->getAuthDaoService();
        if (!empty($cookieValue)) {
            $queryParam['cartSessionId'] = $cookieValue;           
            $cartObject = $authDaoService->getEntityByParameterList($queryParam, Constant::ENTITY_CART);
            if (is_object($cartObject) &&  $cartObject->getUserId() == 0) {
                $cartObject->setUserId($userId);
                $this->save($cartObject);
            }
        }
    }

    /*
     * Get cart using userId and cookie
     */

    public function getCartUsingUserIdAndCookie($cookieValue, $userId)
    {
        $authDaoService = $this->getAuthDaoService();
        $queryParam['cartSessionId'] = $cookieValue;
        $queryParam['userId'] = $userId;
        $cartObject = $authDaoService->getEntityByParameterList($queryParam, Constant::ENTITY_CART);
        return $cartObject;
    }
    
}
