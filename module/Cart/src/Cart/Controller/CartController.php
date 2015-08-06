<?php

/**
 * Cart Controller
 * 
 * PHP version 5
 * 
 * @category   Cart
 * @package    Controller
 * @subpackage 
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2015 CoS
 * @license    http://www.cos.com Proprietary 
 * @version    GIT: 1.7
 * @link       http://www.cos.com 
 * 
 */

namespace Cart\Controller;

use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class CartController extends \Application\Controller\AbstractCoreController
{
    
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

    public function displayCartAction()
    {        
        $viewModel = new ViewModel();                  
        $deleteFlag = $this->params('flag');
        $cartManagerService = $this->getCartManagerService();
        $cookieValue = $this->getCustomCookieValue('insert_cart');
        
        $config = $this->getApplicationConfig();
        $applicationEnv = getenv('APPLICATION_ENV');
        $cmsBaseLink = $config['cmsBaseUrl'][$applicationEnv];
        $userId = 0;
        if ($this->getAuthenticationService()->hasIdentity()) {
            $userObject = $this->getAuthenticationService()->getIdentity();
            $userId = $userObject->getId();
        }        
        
        $packageDetails = array();
        if (!empty($cookieValue)) {
             $packageDetails = $cartManagerService->displayPackageUsingCookie($cookieValue, $userId);
        }      
        
        if ($this->getAuthenticationService()->hasIdentity()) {
            $this->layout()->setTemplate('layout/userAccountLayout');
        }
                        
        $viewModel->setVariable('flag', $deleteFlag);
        $viewModel->setVariable('cmsBaseUrl', $cmsBaseLink);
        $viewModel->setVariable('cartObject', $packageDetails);
        return $viewModel;
    }
    
    /*
     * Delete cart item
     */

    public function deleteCartItemAction()
    {              
        $request = $this->getRequest();  
        $cartManagerService = $this->getCartManagerService();
        $responseData = $cartManagerService->deleteCartItem($request->getPost(), $this);                     
        $response = new JsonModel($responseData);
        return $response;
    }

}