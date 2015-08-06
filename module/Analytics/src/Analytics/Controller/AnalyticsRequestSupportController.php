<?php

namespace Analytics\Controller;
use Zend\View\Model\ViewModel;
use Zend\Session\Container as SessionContainer;
use Zend\View\Model\JsonModel;

class AnalyticsRequestSupportController extends \Application\Controller\AbstractCoreController
{
    
    /**
     *  
     * Get analytics manager service
     */
    public function getAnalyticsService()
    {
        return $this->getServiceLocator()->get('analytics_manager_service');
    }
    
    public function __init() {
        $roleSession = new SessionContainer('role');
        if (!$this->getAuthenticationService()->hasIdentity()) {
            return $this->redirect()->toUrl($this->url()->fromRoute('sign-in'));
        } elseif ($roleSession->roleName == 'admin'){
            return $this->redirect()->toUrl($this->url()->fromRoute('manage-users'));
        }
    }
    
    public function requestSupportAction(){        
        if ($this->__init()) {
            return $this->__init();
        }
        
        $viewModel = new ViewModel();
        $request = $this->getRequest();
        $requestSupportForm = new \Analytics\Form\RequestSupport();        
        
        $categoryArray = array('Select Category','Audit Analysis Generation' => 'Audit Analysis Generation', 'Desktop Utility'=>'Desktop Utility', 'General Inquiry'=>'General Inquiry', 'Rulebook configuration'=>'Rulebook configuration');
        $requestSupportForm->get('category')->setValueOptions($categoryArray);        
        
        
        $userObject = $this->getAuthenticationService()->getIdentity();        
        $userId = $userObject->getId();
        $userName = $userObject->getUsername();
        $requestSupportForm->get('email')->setValue($userName);
        $analyticsManager = $this->getAnalyticsService();
        $extractNames = $analyticsManager->getAnalysisRequestName($userId);
        
        if(!empty($extractNames)){
            $requestSupportForm->get('analysis_name')->setValueOptions($extractNames);
        }
        
        if ($request->isPost()) {
            $postDataArray = $request->getPost()->toArray();            
            if(!empty($postDataArray)){
                if(isset($postDataArray['cancel_request_support'])){
                    $message = 'Request Has been cancelled';
                    $this->setFlashMessage('cancelMessage', $message);
                    $this->redirect()->toUrl($this->url()->fromRoute('user-account'));
                } else {
                    $response = $analyticsManager->sendRequestSupportMail($userObject, $postDataArray);                    
                    if ('fail' == $response['status']) {
                        $viewModel->setVariable('errorMessage', $response);    
                        $requestSupportForm = $analyticsManager->populateRequestSupportForm($requestSupportForm, $postDataArray);   
                    } else {
                        $message = 'Request has been submitted successfully';
                        $this->setFlashMessage('successMessage', $message);
                        $this->redirect()->toUrl($this->url()->fromRoute('user-account'));
                    }
                }
            }
        }
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/app/analysis-request.js');
        $viewModel->setVariable('requestSupportForm', $requestSupportForm);
        $viewModel->setVariable('categoryArray', $categoryArray);
        $viewModel->setVariable('userLoginName', $userName);
        $this->layout()->setTemplate('layout/userAccountLayout');
        return $viewModel;
    }
    
}

?>
