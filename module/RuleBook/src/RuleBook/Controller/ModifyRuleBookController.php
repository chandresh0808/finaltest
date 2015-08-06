<?php

namespace RuleBook\Controller;
use Zend\View\Model\ViewModel;
use Zend\Session\Container as SessionContainer;
use Zend\View\Model\JsonModel;
use Application\View\Helper\MobileDetect;
class ModifyRuleBookController extends \Application\Controller\AbstractCoreController
{
    /**
     *
     * common function like constructor
     */
    public function __init() {
        
        $roleSession = new SessionContainer('role');        
        if (!$this->getAuthenticationService()->hasIdentity()) {
            return $this->redirect()->toUrl($this->url()->fromRoute('sign-in'));
        } elseif ($roleSession->roleName == 'associate') {
            return $this->redirect()->toUrl($this->url()->fromRoute('user-account'));
        } elseif ($roleSession->roleName == 'admin') {
            return $this->redirect()->toUrl($this->url()->fromRoute('manage-users'));
        }
        $mobileDetectService = new MobileDetect();
        $isMobile = $mobileDetectService->isMobile();
        $isTablet = $mobileDetectService->isTablet();
    
        if ($isMobile == true || $isTablet == true) {
            return $this->redirect()->toUrl($this->url()->fromRoute('analysis-reports'));
        }
    }       
    
    
    /**
     *  
     * Get rulebook manager service
     */
    public function getRuleBookService()
    {
        return $this->getServiceLocator()->get('rule_book_manager_service');
    }
    
    /*
     * Configuring rulebook
     */
    public function modifyRulebookAction(){               
        
        if ($this->__init()) {            
            return $this->__init();             
        }
        
        $rulebookId = $this->params('modifyparameter') ;        
        if ($rulebookId == 1) {
            return $this->redirect()->toUrl($this->url()->fromRoute('rulebook-list'));
        }
        
        $userObject = $this->getAuthenticationService()->getIdentity();
        $userId = $userObject->getId();
        
        $viewModel = new ViewModel();
        $ruleBookManager = $this->getRuleBookService();        
        $riskList = $ruleBookManager->getRuleBookHasRiskArray($rulebookId);        
        
        $ruleBookData = $ruleBookManager->getRuleBookObj($rulebookId);
        if (!is_object($ruleBookData)) {
            return $this->redirect()->toUrl($this->url()->fromRoute('rulebook-list'));
        }
        $lastUpdatedDateTimeObj = $ruleBookData->getUpdatedDtTm();        
        $analysisRequestListObj = $ruleBookData->getAnalysisRequestList();
        
//        $defaultJobFunction = $ruleBookManager->getDefaultJobFunction($userId);
//        $defaultTransaction = $ruleBookManager->getDefaultTransaction($userId);
        
        $viewModel->setVariable('defaultJobFunction', $defaultJobFunction);
        $viewModel->setVariable('defaultTransaction', $defaultTransaction);
        $viewModel->setVariable('lastUpdatedRuleBook', $lastUpdatedDateTimeObj->format('m/d/Y'));
        $viewModel->setVariable('ruleBookDescription', $ruleBookData->getDescription());
        $viewModel->setVariable('ruleBookName', $ruleBookData->getName());
        $viewModel->setVariable('ruleBookId', $ruleBookData->getId());
        $viewModel->setVariable('selectedRulebookId', $rulebookId);
        
        if (is_object($analysisRequestListObj)) {
            $lastExtractedDateTimeObj = $analysisRequestListObj->getUpdatedDtTm();
            
            if (is_object($lastExtractedDateTimeObj)) {
                $viewModel->setVariable('analysisRequestLastExtractedDate', $lastExtractedDateTimeObj->format('m/d/Y'));
            } else {
                $viewModel->setVariable('analysisRequestLastExtractedDate', 'NA');
            }
            
        } else {
            $viewModel->setVariable('analysisRequestLastExtractedDate', 'NA');
        }
        
        if(!empty($riskList)){
            $viewModel->setVariable('riskList', $riskList);
            
            $defaultRulebookHasRiskId = $riskList[0]['id'];
            $rulebookHasRiskId[] = $riskList[0]['id'];
            $viewModel->setVariable('defaultRulebookHasRiskId', $defaultRulebookHasRiskId);
            $jobFunctionList = $ruleBookManager->getRiskHasJobFunction($rulebookHasRiskId);                        
        }
        
        //$this->getServiceLocator()->get('viewhelpermanager')->get('headLink')->appendStylesheet('/css/bootstrap-multiselect.css');
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/app/modify-rulebook.js');
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/bootstrap-multiselect.js');
        
        $this->layout()->setTemplate('layout/userAccountLayout');
        
        return $viewModel;
    }
    
    public function defaultJobFunctionListAction() {        
        if (!$this->getAuthenticationService()->hasIdentity()) {
            $redirectUrl['redirect'] = $this->url()->fromRoute('sign-in');
            $response = new JsonModel($redirectUrl);
            return $response;            
        }
        $this->__init();
                
        $ruleBookManager = $this->getRuleBookService();        
        $userObject = $this->getAuthenticationService()->getIdentity();
        $userId = $userObject->getId();
        $defaultJobFunctionList = $ruleBookManager->getDefaultJobFunction($userId);
        $response = new JsonModel($defaultJobFunctionList);
        return $response;
    }
    
    
    public function defaultTransactionListAction() {
        if (!$this->getAuthenticationService()->hasIdentity()) {
            $redirectUrl['redirect'] = $this->url()->fromRoute('sign-in');
            $response = new JsonModel($redirectUrl);
            return $response; 
        }
        $this->__init();
        
        $ruleBookManager = $this->getRuleBookService();        
        $userObject = $this->getAuthenticationService()->getIdentity();
        $userId = $userObject->getId();
        $defaultTransactionList = $ruleBookManager->getDefaultTransaction($userId);
        $response = new JsonModel($defaultTransactionList);
        return $response;
    }
    
    
    public function onSelectJobFunctionListAction(){
        if (!$this->getAuthenticationService()->hasIdentity()) {
            $redirectUrl['redirect'] = $this->url()->fromRoute('sign-in');
            $response = new JsonModel($redirectUrl);
            return $response; 
        }
        $this->__init();
        
        $postData = $this->getRequest()->getPost();        
        $ruleBookManager = $this->getRuleBookService();
        $jobFunctionList = $ruleBookManager->getRiskHasJobFunction(array($postData['rulebookHasRisk_id']));
        $jobFunctionList = array_reverse($jobFunctionList);
        $response = new JsonModel($jobFunctionList);
        
        return $response;
    }
    
    public function onSelectTransactionListAction(){
        if (!$this->getAuthenticationService()->hasIdentity()) {
            $redirectUrl['redirect'] = $this->url()->fromRoute('sign-in');
            $response = new JsonModel($redirectUrl);
            return $response; 
        }
        $this->__init();
        
        $postData = $this->getRequest()->getPost();        
        $ruleBookManager = $this->getRuleBookService();
        $transactionList = $ruleBookManager->getjobFunctionHasTransaction(array($postData['riskHasJobFunction_id']));       
        $transactionList = array_reverse($transactionList);
        $response = new JsonModel($transactionList);
        return $response;
    }
    
    public function addRiskAction(){
        if (!$this->getAuthenticationService()->hasIdentity()) {
            $redirectUrl['redirect'] = $this->url()->fromRoute('sign-in');
            $response = new JsonModel($redirectUrl);
            return $response; 
        }
        $this->__init();
        
        $postData = $this->getRequest()->getPost();
        $ruleBookManager = $this->getRuleBookService();
        
        $userObject = $this->getAuthenticationService()->getIdentity();
        $rulebookArray = $ruleBookManager->getRuleBookArray($userObject->getId(), $postData['rulebook_id']);
        
        if (empty($rulebookArray)) {
            return $this->redirect()->toUrl($this->url()->fromRoute('sign-in'));
        }
        
        $responseArray = $ruleBookManager->createRisk($postData);
        $response = new JsonModel($responseArray);
        return $response;
    }
    
    public function addJobFunctionAction(){        
        if (!$this->getAuthenticationService()->hasIdentity()) {
            $redirectUrl['redirect'] = $this->url()->fromRoute('sign-in');
            $response = new JsonModel($redirectUrl);
            return $response; 
        }
        $this->__init();
        
        $postData = $this->getRequest()->getPost();        
        $ruleBookManager = $this->getRuleBookService();
        
        $userObject = $this->getAuthenticationService()->getIdentity();
        $userId = $userObject->getId();
        $rulebookArray = $ruleBookManager->getRuleBookArray($userId, $postData['rulebook_id']);
        
        if (empty($rulebookArray)) {
            return $this->redirect()->toUrl($this->url()->fromRoute('sign-in'));
        }
        
        $responseArray = $ruleBookManager->createJobFunction($postData, $userId);
        $response = new JsonModel($responseArray);
        return $response;        
    }    
    
    public function addTransactionAction(){
        if (!$this->getAuthenticationService()->hasIdentity()) {
            $redirectUrl['redirect'] = $this->url()->fromRoute('sign-in');
            $response = new JsonModel($redirectUrl);
            return $response; 
        }
        $this->__init();
        
        $postData = $this->getRequest()->getPost();         
        $ruleBookManager = $this->getRuleBookService();
        
        $userObject = $this->getAuthenticationService()->getIdentity();
        $userId = $userObject->getId();
        $rulebookArray = $ruleBookManager->getRuleBookArray($userId, $postData['rulebook_id']);
        
        if (empty($rulebookArray)) {
            return $this->redirect()->toUrl($this->url()->fromRoute('sign-in'));
        }
        
        $responseArray = $ruleBookManager->createTransaction($postData, $userId);
        $response = new JsonModel($responseArray);
        return $response;
    }
    

    public function deleteRiskAction(){        
        if (!$this->getAuthenticationService()->hasIdentity()) {
            $redirectUrl['redirect'] = $this->url()->fromRoute('sign-in');
            $response = new JsonModel($redirectUrl);
            return $response; 
        }
        $this->__init();
        
        $postData = $this->getRequest()->getPost(); 
        $ruleBookManager = $this->getRuleBookService();
        
        $userObject = $this->getAuthenticationService()->getIdentity();
        $rulebookArray = $ruleBookManager->getRuleBookArray($userObject->getId(), $postData['rulebook_id']);
        
        if (empty($rulebookArray)) {
            return $this->redirect()->toUrl($this->url()->fromRoute('sign-in'));
        }
        
        $responseArray = $ruleBookManager->deleteRisk($postData);
        $response = new JsonModel($responseArray);
        return $response;
    }
    
    
    /*
     * Edit risk
     */
    public function editRiskAction() {
        if (!$this->getAuthenticationService()->hasIdentity()) {
            $redirectUrl['redirect'] = $this->url()->fromRoute('sign-in');
            $response = new JsonModel($redirectUrl);
            return $response; 
        }
        $this->__init();
        
        $postData = $this->getRequest()->getPost(); 
        $ruleBookManager = $this->getRuleBookService();
        $response = $ruleBookManager->editRisk($postData);
        $responseArray['status'] = 'fail';
        $responseArray['message'] = 'Not able to update risk';
        if($response) {
            $responseArray['status'] = 'success';
            $responseArray['message'] = 'Risk has been updated successfully';
        } 
        $response = new JsonModel($responseArray);
        return $response;
    }
    

    public function deleteJobFunctionAction(){        
        if (!$this->getAuthenticationService()->hasIdentity()) {
            $redirectUrl['redirect'] = $this->url()->fromRoute('sign-in');
            $response = new JsonModel($redirectUrl);
            return $response; 
        }
        $this->__init();
        
        $postData = $this->getRequest()->getPost(); 
        $ruleBookManager = $this->getRuleBookService();
        
        $userObject = $this->getAuthenticationService()->getIdentity();
        $userId = $userObject->getId();
        $rulebookArray = $ruleBookManager->getRuleBookArray($userObject->getId(), $postData['rulebook_id']);
        
        if (empty($rulebookArray)) {
            return $this->redirect()->toUrl($this->url()->fromRoute('sign-in'));
        }        
        $responseArray = $ruleBookManager->deleteJobFunction($postData['riskHasJobFunction_id']);
        $response = new JsonModel($responseArray);
        return $response;
    }
    
    
    /*
     * Edit Job Function
     */
    public function editJobFunctionAction() {
        if (!$this->getAuthenticationService()->hasIdentity()) {
            $redirectUrl['redirect'] = $this->url()->fromRoute('sign-in');
            $response = new JsonModel($redirectUrl);
            return $response; 
        }
        $this->__init();
        
        $postData = $this->getRequest()->getPost(); 
        $userObject = $this->getAuthenticationService()->getIdentity();
        $userId = $userObject->getId();
        $ruleBookManager = $this->getRuleBookService();
        $response = $ruleBookManager->editJobFunction($postData, $userId);
        if ($response['status'] == 'success') {
            $response['message'] = 'Job function updated successfully';
        }
        $response = new JsonModel($response);
        return $response;
    }
    

    public function deleteTransactionAction(){        
        if (!$this->getAuthenticationService()->hasIdentity()) {
            $redirectUrl['redirect'] = $this->url()->fromRoute('sign-in');
            $response = new JsonModel($redirectUrl);
            return $response; 
        }
        $this->__init();
        
        $postData = $this->getRequest()->getPost();        
        $ruleBookManager = $this->getRuleBookService();
        
        $userObject = $this->getAuthenticationService()->getIdentity();
        $userId = $userObject->getId();
        $rulebookArray = $ruleBookManager->getRuleBookArray($userId, $postData['rulebook_id']);
        
        if (empty($rulebookArray)) {
            return $this->redirect()->toUrl($this->url()->fromRoute('sign-in'));
        }
        
        $responseArray = $ruleBookManager->deleteTransaction($postData['jobFunctionHasTransaction_id']);
        $response = new JsonModel($responseArray);
        return $response;
    }
        
     /*
     * Edit Job Function
     */
    public function editTransactionAction() {
        if (!$this->getAuthenticationService()->hasIdentity()) {
            $redirectUrl['redirect'] = $this->url()->fromRoute('sign-in');
            $response = new JsonModel($redirectUrl);
            return $response; 
        }
        $this->__init();
        
        $postData = $this->getRequest()->getPost();         
        $userObject = $this->getAuthenticationService()->getIdentity();
        $userId = $userObject->getId();
        $ruleBookManager = $this->getRuleBookService();        
        $response = $ruleBookManager->editTransaction($postData, $userId);
        if ($response['status'] == 'success') {
            $response['message'] = 'Transaction updated successfully';
        }
        $response = new JsonModel($response);
        return $response;
    }
    
    
    /*
     * Edit Rulebook
     */
    public function editRulebookAction() {        
        if (!$this->getAuthenticationService()->hasIdentity()) {
            $redirectUrl['redirect'] = $this->url()->fromRoute('sign-in');
            $response = new JsonModel($redirectUrl);
            return $response; 
        }
        $this->__init();
        
        $postData = $this->getRequest()->getPost();         
        $ruleBookManager = $this->getRuleBookService();
        $userObject = $this->getAuthenticationService()->getIdentity();
        $responseArray = $ruleBookManager->editRulebook($postData, $userObject);        
        $response = new JsonModel($responseArray);
        return $response;
    }
    
}
?>
