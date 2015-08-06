<?php

/**
 * Package Controller
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

namespace Package\Controller;

use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Session\Container as SessionContainer; 

class PackageController extends \Application\Controller\AbstractCoreController
{

    /**
     * Get package manager Service instance
     * 
     * @return Cron\Model\CronManager
     */
    public function getPackageManagerService()
    {
        return $this->getServiceLocator()->get('package_manager_service');
    }

    /*
     * Display the package pricing
     */

    public function priceAction()
    {
        $roleSession = new SessionContainer('role');
        if ($roleSession->roleName == 'associate') {
            return $this->redirect()->toUrl($this->url()->fromRoute('user-account'));
        } elseif ($roleSession->roleName == 'admin') {
            return $this->redirect()->toUrl($this->url()->fromRoute('manage-users'));
        }
        $uri = $_SERVER['REQUEST_URI'];
        $request = $this->getRequest();
        $viewModel = new ViewModel();
        $packageManagerService = $this->getPackageManagerService();
        $defaultPackagePriceArray = $packageManagerService->getDefaultPackagePriceList();
        $viewModel->setVariable('defaultPackagePriceArray', $defaultPackagePriceArray);
        
        if (strpos($uri,'purchase-analysis') !== false) {
            $this->layout()->setTemplate('layout/userAccountLayout');
        } else {
            $this->layout()->setTemplate('layout/cmsLayout');
        }
        
        return $viewModel;
    }
    
    /*
     * Add package 
     */
    
    public function addPackageAction() {        
        $request = $this->getRequest();               
        $packageManagerService = $this->getPackageManagerService();
        if ($request->isPost()) {
            
            $cookieValue = $this->getCustomCookieValue('insert_cart');                       
            $postDataArray = $request->getPost()->toArray();
            $postDataArray['user_id'] = 0;
            if ($this->getAuthenticationService()->hasIdentity()) {
                $userObject = $this->getAuthenticationService()->getIdentity();
                $postDataArray['user_id'] = $userObject->getId();
                $postDataArray['email'] = $userObject->getUsername();
            }                                
            $responseData = $packageManagerService->addPackage($postDataArray, $this, $cookieValue);            
            $response = new JsonModel($responseData);
            return $response;
            
        }
        
    }

}