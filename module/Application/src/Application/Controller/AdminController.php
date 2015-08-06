<?php

/**
 * Defines a Application Module functionality
 * 
 * PHP version 5
 * 
 * @category   Module
 * @package    Application
 * @subpackage Controller
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2015 CoS
 * @license    http://www.costrategix.com Proprietary 
 * @version    GIT: 1.7
 * @link       http://www.costrategix.com 
 * 
 */

namespace Application\Controller;

use Application\Controller\AbstractCoreController as AbstractCoreController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;  
use Zend\Session\Container as SessionContainer;

class AdminController extends AbstractCoreController
{
    
    
    public function __init(){
        $roleSession = new SessionContainer('role');
        if (!$this->getAuthenticationService()->hasIdentity()) {            
            return $this->redirect()->toUrl($this->url()->fromRoute('admin-login'));
        } elseif ($roleSession->roleName == 'associate') {
            return $this->redirect()->toUrl($this->url()->fromRoute('user-account'));
        } elseif ($roleSession->roleName == 'user') {
            return $this->redirect()->toUrl($this->url()->fromRoute('analysis-reports'));
        }
    }
    
   /*
    * List system activity
    */
    public function listSystemActivityAction() {
        $this->__init();
        $viewModel = new ViewModel();
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/app/system-activity.js');
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/app/data-table.js');
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/jquery.dataTables.min.js');
        $this->layout()->setTemplate('layout/adminUserAccountLayout');
        return $viewModel;
    }
    
    
    /*
     * Get analytics reports data
     */

    public function dataSystemActivityAction()
    {
        $this->__init();
        $paramArray = $this->params()->fromQuery();           
        $applicationManager = $this->getApplicationManagerService();
        $systemActivityData = $applicationManager->dataForSystemActivity($paramArray);
        $systemActivityData['aaData'] = $this->getDataArrayFromLazyLoadingObject($systemActivityData['aaData']);
        $response = new JsonModel($systemActivityData);
        return $response;
    }
    
}

