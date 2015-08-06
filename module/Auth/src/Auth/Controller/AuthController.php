<?php

/**
 * Auth Controller
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

namespace Auth\Controller;

use Zend\View\Model\ViewModel;
use Zend\Session\Container as SessionContainer;
use Zend\View\Model\JsonModel;

class AuthController extends \Application\Controller\AbstractCoreController
{

    /**
     * Login user
     */
    public function loginAction()
    {

        $roleSession = new SessionContainer('role');
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
        $signInForm = new \Auth\Form\Login();
        $request = $this->getRequest();
        $authenticationManager = $this->getAuthManagerService();

        $applicationEnv = getenv('APPLICATION_ENV');
        $config = $this->getServiceLocator()->get('Config');
        $cmsBaseUrl = $config['cmsBaseUrl'][$applicationEnv];

        if ($request->isPost()) {
            $this->getAuthenticationService()->clearIdentity();
            $response = $authenticationManager->authenticate($request->getPost(), $signInForm);
            if ($response['success']) {

                $userId = null;
                if ($this->getAuthenticationService()->hasIdentity()) {
                    $userObject = $this->getAuthenticationService()->getIdentity();
                    $userId = $userObject->getId();
                }

                $cookieValue = $this->getCustomCookieValue('insert_cart');
                $loginSession = new SessionContainer('previous_ref_link');
                $previousUrl = $loginSession->ref;
                if (!empty($cookieValue)) {
                    $authenticationManager->updateCartWithUserIdUsingCookie($cookieValue, $userId);
                    unset($loginSession->ref);
                }
                $cartObject = $authenticationManager->getCartUsingUserIdAndCookie($cookieValue, $userId);
                
                if (is_object($cartObject)) {
                    return $this->redirect()->toUrl($this->url()->fromRoute('checkout'));
                }
                return $this->redirect()->toUrl($this->url()->fromRoute('analysis-reports'));
            } else {
                if (isset($response['blocked'])) {
                    $this->setFlashMessage('errorMessage', $response['blocked']);
                } else {
                    $this->setFlashMessage('errorMessage', 'Sorry, the email address you entered does not exist or is not activated in the system.');
                }
                return $this->redirect()->toUrl($this->url()->fromRoute('sign-in'));
            }
        }

        $successMessage = $this->getFlashMessage('successMessage');
        $errorMessage = $this->getFlashMessage('errorMessage');

        if (!empty($errorMessage)) {
            $viewModel->setVariable('errorMessage', $errorMessage);
        }

        if (!empty($successMessage)) {
            $viewModel->setVariable('successMessage', $successMessage);
        }

        $viewModel->setVariable('cmsBaseUrl', $cmsBaseUrl);
        $viewModel->setVariable('signInForm', $signInForm);
        return $viewModel;
    }

    /*
     * Logout user
     */

    public function logoutAction()
    {
        $this->getAuthenticationService()->clearIdentity();

        /*Delete cart cookie*/
        $roleSession = new SessionContainer('role');
        unset($roleSession->roleName);
        
        $cartSession = new SessionContainer('cart');
        unset($cartSession->cartId);
        //\Application\Model\Utility::deleteCustomCookie($this);

        return $this->redirect()->toUrl($this->url()->fromRoute('sign-in'));
    }

    /*
     * forgot password action
     */

    public function forgotPasswordAction()
    {
        $roleSession = new SessionContainer('role');
        if ($this->getAuthenticationService()->hasIdentity()) {
            if ($roleSession->roleName == 'user') {
                $this->redirect()->toUrl($this->url()->fromRoute('analysis-reports'));
            } elseif ($roleSession->roleName == 'associate') {
                $this->redirect()->toUrl($this->url()->fromRoute('user-account'));
            } elseif ($roleSession->roleName == 'admin') {
                $this->redirect()->toUrl($this->url()->fromRoute('manage-users'));
            }
        }

        $authenticationManager = $this->getAuthManagerService();
        $viewModel = new ViewModel();
        $forgotPasswordForm = new \Auth\Form\ForgotPassword();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $response = $authenticationManager->sendForgotPasswordVerificationCode($request->getPost(), $forgotPasswordForm);
            if ($response['success']) {
                $this->setFlashMessage('successMessage', 'Please check the mail for direction to change your password');
                return $this->redirect()->toUrl($this->url()->fromRoute('sign-in'));
            } else {
                $this->setFlashMessage('errorMessage', $response['error_message']);
                $forgotPasswordForm = $response['forgot_password_form'];
                return $this->redirect()->toUrl($this->url()->fromRoute('forgot-password'));
            }
        }

        $errorMessage = $this->getFlashMessage('errorMessage');

        if (!empty($errorMessage)) {
            $viewModel->setVariable('errorMessage', $errorMessage);
        }

        $viewModel->setVariable('forgotPasswordForm', $forgotPasswordForm);
        return $viewModel;
    }

    /*
     * Activate password
     * 
     */

    public function resetPasswordAction()
    {
        $roleSession = new SessionContainer('role');
        if ($this->getAuthenticationService()->hasIdentity()) {
            if ($roleSession->roleName == 'user') {
                $this->redirect()->toUrl($this->url()->fromRoute('analysis-reports'));
            } elseif ($roleSession->roleName == 'associate') {
                $this->redirect()->toUrl($this->url()->fromRoute('user-account'));
            } elseif ($roleSession->roleName == 'admin') {
                $this->redirect()->toUrl($this->url()->fromRoute('manage-users'));
            }
        }

        $resetForgotPasswordForm = new \Auth\Form\ResetPassword();
        $request = $this->getRequest();
        $viewModel = new ViewModel();

        $authenticationManager = $this->getAuthManagerService();

        $current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $explodedUrl = explode('/', $current_url);
        $index = count($explodedUrl);
        $type = $explodedUrl[$index - 2];

        if ($type == 'set-password') {
            $viewModel->setVariable("setPassword", 1);
        }

        if ($request->isPost()) {
            $postData = $request->getPost();
            $response = $authenticationManager->resetForgotPasswordVerificationCode($request->getPost());
            if ($response['success']) {
                if ($type == 'set-password') {
                    $this->setFlashMessage('successMessage', 'Password has been reset successfully. Please login with the updated login credendtials.');
                } else {
                    $this->setFlashMessage('successMessage', 'Password has been set successfully. Please login with the updated login credendtials.');
                }
                return $this->redirect()->toUrl($this->url()->fromRoute('sign-in'));
            } else {
                $this->setFlashMessage('errorMessage', $response['error_message']);
                return $this->redirect()->toUrl($this->url()->fromRoute('reset-password', array('reset_code' => $postData['verification_code'])));
            }
        }

        $resetPasswordVerificationCode = $this->params('reset_code');

        if (!$resetPasswordVerificationCode) {
            $this->setFlashMessage('errorMessage', array('Invalid verification code'));
            return $this->redirect()->toUrl($this->url()->fromRoute('sign-in'));
        }

        $errorMessage = $this->getFlashMessage('errorMessage');

        if (!empty($errorMessage)) {
            $viewModel->setVariable('errorMessage', $errorMessage);
        }


        $viewModel->setVariable('verification_code', $resetPasswordVerificationCode);
        $viewModel->setVariable('resetForgotPasswordForm', $resetForgotPasswordForm);
        return $viewModel;
    }

}