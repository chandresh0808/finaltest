<?php

/**
 * Payment Controller
 * 
 * PHP version 5
 * 
 * @category   Package
 * @package    Controller
 * @subpackage 
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2015 CoS
 * @license    http://www.cos.com Proprietary 
 * @version    GIT: 1.7
 * @link       http://www.cos.com 
 * 
 */

namespace Payment\Controller;

use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container; 

class PaymentController extends \Application\Controller\AbstractCoreController
{

    /**
     * Get package manager Service instance
     * 
     * @return Cron\Model\CronManager
     */
    public function getPaymentManagerService()
    {
        return $this->getServiceLocator()->get('payment_manager_service');
    }

    /**
     * Get package manager Service instance
     * 
     * @return Cron\Model\CronManager
     */
    public function getCartManagerService()
    {
        return $this->getServiceLocator()->get('cart_manager_service');
    }

    /*
     * Display the package pricing
     */

    public function checkoutAction()
    {
        $roleSession = new Container('role');
        if (!$this->getAuthenticationService()->hasIdentity()) {
             $loginSession = new Container('previous_ref_link');
             $loginSession->ref = 'checkout';
             $this->redirect()->toUrl($this->url()->fromRoute('sign-in'));
        } elseif ($roleSession->roleName == 'associate') {
            return $this->redirect()->toUrl($this->url()->fromRoute('user-account'));
        } elseif ($roleSession->roleName == 'admin') {
            return $this->redirect()->toUrl($this->url()->fromRoute('manage-users'));
        }              
        
        $viewModel = new ViewModel();
        $userObject = $this->getAuthenticationService()->getIdentity();
        $paymentManagerService = $this->getPaymentManagerService();
        $request = $this->getRequest();
        $cartManagerService = $this->getCartManagerService();
        $checkoutForm = new \Payment\Form\CheckoutForm();
        
        $applicationEnv = getenv('APPLICATION_ENV');
        $config = $this->getApplicationConfig();
        $baseCMSUrl = $config['cmsBaseUrl'][$applicationEnv];
        $viewModel->setVariable('baseCMSUrl', $baseCMSUrl);
        
        $cookieValue = $this->getCustomCookieValue('insert_cart');
        if (!empty($cookieValue)) {
            
            if (is_object($userObject)){
                $userId = $userObject->getId();
            }
            
            $cartObject = $cartManagerService->displayPackageUsingCookie($cookieValue, $userId);
            $viewModel->setVariable('cartObject', $cartObject);
        } else {
            $this->redirect()->toUrl($baseCMSUrl . "/pricing");
        }
        
       
        $cartSession = new Container('cart');
        $cartId = $cartSession->cartId;
        
  
        $orderAndPaymentDetails = array();
        if (!empty($cartId)) {
            $orderAndPaymentDetails = $paymentManagerService->getOrderAndPaymentDetails($cartId);            
        } 
        $checkoutForm = $paymentManagerService->populateCheckoutForm($checkoutForm, $orderAndPaymentDetails, $userObject);
        
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/jquery.mask.js');
                       
        if ($request->isPost()) {
            $postData = $request->getPost();
            $postData['user_id'] = $userObject->getId();
            if (!empty($cartId)) {
                $responseArray = $paymentManagerService->updateOrderDetails($cartId, $postData);
            } else {
                $responseArray = $paymentManagerService->saveOrderDetails($postData, $checkoutForm);
            }
                                                            
            if ('success' == $responseArray['status']) {
                return $this->redirect()->toUrl($this->url()->fromRoute('confirm-order'));
            } else {
                $viewModel->setVariable("errorMessage", $responseArray['error_message']);
                $checkoutForm = $responseArray['checkout_form'];
            }
        }
        
        $errorMessage = $this->getFlashMessage('errorMessage');
        if (!empty($errorMessage)) {
            $viewModel->setVariable('redirectErrorMessage', $errorMessage);
        }
        $this->layout()->setTemplate('layout/userAccountLayout');
        $viewModel->setVariable('checkoutForm', $checkoutForm);
        $viewModel->setVariable('postData', $postData);

        return $viewModel;
    }

    /*
     * Confirm order action
     */

    public function confirmOrderAction()
    {   
        $roleSession = new Container('role');
        if (!$this->getAuthenticationService()->hasIdentity()) {             
             $this->redirect()->toUrl($this->url()->fromRoute('sign-in'));
        } elseif ($roleSession->roleName == 'associate') {
            return $this->redirect()->toUrl($this->url()->fromRoute('user-account'));
        } elseif ($roleSession->roleName == 'admin') {
            return $this->redirect()->toUrl($this->url()->fromRoute('manage-users'));
        }
                
        $viewModel = new ViewModel();
        $cartSession = new Container('cart');      
        $cartId = $cartSession->cartId;      
        $request = $this->getRequest();
        
                
        $applicationEnv = getenv('APPLICATION_ENV');
        $config = $this->getApplicationConfig();
        $baseCMSUrl = $config['cmsBaseUrl'][$applicationEnv];
        $viewModel->setVariable('baseCMSUrl', $baseCMSUrl);
        
        if (empty($cartId)) {
           $this->redirect()->toUrl($baseCMSUrl . "/pricing");
        }
        
        $paymentManagerService = $this->getPaymentManagerService(); 
        $orderAndPaymentDetails = $paymentManagerService->getOrderAndPaymentDetails($cartId);   
                 
        if ($request->isPost()) {                     
            $responseArray = $paymentManagerService->placeOrder($orderAndPaymentDetails, $this);          
                                                            
            if ('success' == $responseArray['status']) {
                $this->redirect()->toUrl($this->url()->fromRoute('thank-you', array('order_id'=> $orderAndPaymentDetails['order_id'])));
            } else {
                $message = 'Woops!, Some thing went wrong';
                
                if (isset($responseArray['errorMessage'])) {
                    $message = $responseArray['errorMessage'];
                }
                                
                $this->setFlashMessage('errorMessage', $message);
                $this->redirect()->toUrl($this->url()->fromRoute('checkout'));
            }
        }
        $this->layout()->setTemplate('layout/userAccountLayout');
        $viewModel->setVariable('orderAndPaymentDetails', $orderAndPaymentDetails);
        return $viewModel;
    }
    
    /*
     * Thank you page
     */
    
    public function thankYouAction () {
        $roleSession = new Container('role');
        if (!$this->getAuthenticationService()->hasIdentity()) {
            $this->redirect()->toUrl($this->url()->fromRoute('sign-in'));
        } elseif ($roleSession->roleName == 'associate') {
            return $this->redirect()->toUrl($this->url()->fromRoute('user-account'));
        } elseif ($roleSession->roleName == 'admin') {
            return $this->redirect()->toUrl($this->url()->fromRoute('manage-users'));
        }
        $cartSession = new Container('Success');      
        $response = $cartSession->response;
       
        if ((!empty($response)) && ($response == 'success')) {
            $paymentManagerService = $this->getPaymentManagerService();
            $viewModel = new ViewModel();
            $orderId = $this->params('order_id');
            $applicationEnv = getenv('APPLICATION_ENV');
            $config = $this->getApplicationConfig();
            $baseCMSUrl = $config['cmsBaseUrl'][$applicationEnv];
            $viewModel->setVariable('baseCMSUrl', $baseCMSUrl);
            $viewModel->setVariable('orderId', $orderId);
            $this->layout()->setTemplate('layout/userAccountLayout');
            $paymentManagerService->deleteCartCookieAndSession($this);
            return $viewModel;
        } else {
            $this->redirect()->toUrl($this->url()->fromRoute('purchase-analysis'));
        }
        
    }

}