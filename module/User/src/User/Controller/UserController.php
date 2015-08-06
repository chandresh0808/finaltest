<?php

/**
 * User Controller
 * 
 * PHP version 5
 * 
 * @category   Auth
 * @package    Controller
 * @subpackage 
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2015 Costrategix
 * @license    http://www.costrategix.com Proprietary 
 * @version    GIT: 1.7
 * @link       http://www.costrategix.com 
 * 
 */

namespace User\Controller;

use Zend\View\Model\ViewModel;
use Zend\Session\Container as SessionContainer;
use Zend\View\Model\JsonModel;

class UserController extends \Application\Controller\AbstractCoreController
{
    
    public function __init() {
        $roleSession = new SessionContainer('role');
        if (!$this->getAuthenticationService()->hasIdentity()) {
            return $this->redirect()->toUrl($this->url()->fromRoute('sign-in'));
        } elseif ($roleSession->roleName == 'associate') {
            return $this->redirect()->toUrl($this->url()->fromRoute('user-account'));
        } elseif ($roleSession->roleName == 'admin') {
            $this->redirect()->toUrl($this->url()->fromRoute('manage-users'));
        }
    }
    
    
    /**
     * Get cron manager Service instance
     * 
     * @return Cron\Model\CronManager
     */
    public function getUserService()
    {
        return $this->getServiceLocator()->get('user_service');
    }

    /*
     * User signup
     * @return \Zend\View\Model\ViewModel
     */

    function signUpAction()
    {

        if ($this->getAuthenticationService()->hasIdentity()) {
           if ($roleSession->roleName == 'user') {
                $this->redirect()->toUrl($this->url()->fromRoute('analysis-reports'));                
            } elseif ($roleSession->roleName == 'associate') {
                $this->redirect()->toUrl($this->url()->fromRoute('user-account'));                
            } elseif ($roleSession->roleName == 'admin') {
                $this->redirect()->toUrl($this->url()->fromRoute('manage-users'));                
            }
        }

        $viewModel = new ViewModel();
        $request = $this->getRequest();
        $userManagerService = $this->getUserManagerService();
        $config = $this->getApplicationConfig();
        $applicationEnv = getenv('APPLICATION_ENV');
        $pubkey = $config['captchaKey'][$applicationEnv]['pubicKey'];
        $privkey = $config['captchaKey'][$applicationEnv]['privateKey'];
        $theme = array('theme' => 'clean', 'lang' => 'en');
        $cmsLink = $config['cmsBaseUrl'][$applicationEnv];
        $viewModel->setVariable('cmsBaseLink', $cmsLink);
        $params['ssl'] = true;
        
        if ('development' == strtolower($applicationEnv)) {
            $params['ssl'] = false;
        }
        $recaptcha = new \ZendService\ReCaptcha\ReCaptcha($pubkey, $privkey, $params, $theme);
        $setRecaptcha = $recaptcha->getHTML();
        $viewModel->setVariable('captcha', $setRecaptcha);

        $signUpForm = new \User\Form\SignUp();
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/jquery.mask.js');
        
        if ($request->isPost()) {
            $postDataArray = $request->getPost()->toArray();

            $captchaResponse = $recaptcha->verify($postDataArray['recaptcha_challenge_field'], $postDataArray['recaptcha_response_field']);

            if ($captchaResponse->isValid()) {
                $signUpForm->setData($postDataArray);
                if ($signUpForm->isValid()) {
                    $response = $userManagerService->createUser($postDataArray, $signUpForm);

                    if ('fail' == $response['status']) {
                        $viewModel->setVariable("errorMessage", $response['error_message']);
                        $signUpForm = $response['sign_up_form'];
                    } else if ('success' == $response['status']) {
                        $message = 'Account created successfully. Please check your mail to activate your account';
                        $this->setFlashMessage('successMessage', $message);
                        $this->redirect()->toUrl($this->url()->fromRoute('sign-in'));
                    }
                }
            } else {
                $response['error_message'] = array('invalid_captcha' => 'Invalid captcha');
                $viewModel->setVariable("errorMessage", $response['error_message']);
                $signUpForm = $this->getUserService()->populateSignUpForm($signUpForm, $postDataArray);
            }
        }
        $viewModel->setVariable('signUpForm', $signUpForm);

        return $viewModel;
    }

    /*
     * Activate user
     * @param string $activationCode
     * 
     * @return bool true/false
     */

    public function activateUserAction()
    {
        $activateCode = $this->params('activation_code');
        $userRole = $this->params('role');
        $userManagerService = $this->getUserManagerService();
        $response = $userManagerService->activateUser($activateCode);        
        if ($response) {
            if ($userRole == 'associate') {
                $setPasswordVerficationCode = $userManagerService->setPassword($activateCode);                 
                if (!empty($setPasswordVerficationCode)) {
                    return $this->redirect()->toRoute('set-password', array('reset_code' => $setPasswordVerficationCode));
                } else {
                    throw new Exception('Not able to active user');
                }
            }
            return $this->redirect()->toUrl($this->url()->fromRoute('sign-in'));
        } else {
            throw new Exception('Not able to active user');
        }
    }

    /*
     * User account 
     */

    public function userAccountAction()
    {        
        $roleSession = new SessionContainer('role');
        if (!$this->getAuthenticationService()->hasIdentity()) {            
            return $this->redirect()->toUrl($this->url()->fromRoute('sign-in'));
        } elseif ($roleSession->roleName == 'admin') {
            $this->redirect()->toUrl($this->url()->fromRoute('manage-users'));
        }
        
        $roleSession = new SessionContainer('role');
        $viewModel = new ViewModel();
        $request = $this->getRequest();
        $userManagerService = $this->getUserManagerService();
        $userAccountForm = new \User\Form\UserAccount();
        $userObject = $this->getAuthenticationService()->getIdentity();
        $userAccountForm = $userManagerService->populateUserAccountForm($userAccountForm, $userObject);               
        $viewModel->setVariable('role', $roleSession->roleName);
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/app/associate-user.js');
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/app/data-table.js');
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/jquery.dataTables.min.js');
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/jquery.mask.js');
        
        if ($request->isPost()) {
            $postDataArray = $request->getPost()->toArray();            
            $response = $userManagerService->updateUserAccount($postDataArray, $userObject, $userAccountForm);

            if ('fail' == $response['status']) {
                $viewModel->setVariable('errorMessage', $response['error_message']);
                $userAccountForm = $response['user_account_form'];
            } else if ('success' == $response['status']) {
                $this->setFlashMessage('successMessage', $response['success_message']);
                $this->redirect()->toUrl($this->url()->fromRoute('user-account'));
            }
        }
        
        /*User credit history*/
        $userCreditHistoryArray = $userManagerService->getUserCreditHistory($userObject);        
        if(!empty($userCreditHistoryArray)){
            $viewModel->setVariable('userCreditHistory', $userCreditHistoryArray);
        }        
        
        $viewModel->setVariable('currentDate', date('m/d/Y'));
        $successMessage = $this->getFlashMessage('successMessage');
        $errorMessage = $this->getFlashMessage('errorMessage');
        $cancelMessage = $this->getFlashMessage('cancelMessage');
        
        if (!empty($errorMessage)) {
            $viewModel->setVariable('errorMessage', $errorMessage);
        }

        if (!empty($successMessage)) {
            $viewModel->setVariable('successMessage', $successMessage);
        }
        
        if (!empty($cancelMessage)) {
            $viewModel->setVariable('cancelMessage', $cancelMessage);
        }
        
        $viewModel->setVariable('userAccountForm', $userAccountForm);
        $this->layout()->setTemplate('layout/userAccountLayout');
        return $viewModel;
    }
    
    /*
     * Add Associate user account
     */
    public function addAssociateUserAction(){
        if (!$this->getAuthenticationService()->hasIdentity()) {
            $redirectUrl['redirect'] = $this->url()->fromRoute('sign-in');
            $response = new JsonModel($redirectUrl);
            return $response; 
        }        
        $this->__init();        
        
        $userManagerService = $this->getUserManagerService();
        $userObject = $this->getAuthenticationService()->getIdentity();
        $postData = $this->getRequest()->getPost(); 
        $responseArray = $userManagerService->createAssociateUser($postData, $userObject);        
        $response = new JsonModel($responseArray);
        return $response;
    }
    
    
    /*
     * List Associate user
     */
    public function listAssociateUserAction(){
        if ($this->__init()) {
            return $this->__init();
        }
        
        $paramArray = $this->params()->fromQuery();
        $userObject = $this->getAuthenticationService()->getIdentity();
        $paramArray['user_id'] = $userObject->getId();
        $userManagerService = $this->getUserManagerService();
        $associateUserData = $userManagerService->dataForAssociateUserList($paramArray);        
        $response = new JsonModel($associateUserData);
        return $response;
    }
    
    
    
    /*
     * Delete Associate user
     */
    public function deleteAssociateUserAction(){
       if (!$this->getAuthenticationService()->hasIdentity()) {
            $redirectUrl['redirect'] = $this->url()->fromRoute('sign-in');
            $response = new JsonModel($redirectUrl);
            return $response; 
       }        
       $this->__init();       
       
       $postData = $this->getRequest()->getPost();       
       $userManagerService = $this->getUserManagerService();
       $associateUserData = $userManagerService->deleteUser($postData['delete-associate-user-id']);       
       $response = new JsonModel($associateUserData);
       return $response;
    }
    
    
}
