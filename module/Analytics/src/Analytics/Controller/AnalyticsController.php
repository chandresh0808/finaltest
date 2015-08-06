<?php

namespace Analytics\Controller;

use Zend\View\Model\ViewModel;
use Zend\Session\Container as SessionContainer;
use Zend\View\Model\JsonModel;

class AnalyticsController extends \Application\Controller\AbstractCoreController
{

    
    public function __init() {
        $roleSession = new SessionContainer('role');
        if (!$this->getAuthenticationService()->hasIdentity()) {
            return $this->redirect()->toUrl($this->url()->fromRoute('sign-in'));
        } elseif ($roleSession->roleName == 'associate') {
            return $this->redirect()->toUrl($this->url()->fromRoute('user-account'));
        } elseif ($roleSession->roleName == 'admin') {
            return $this->redirect()->toUrl($this->url()->fromRoute('manage-users'));
        }
    }    
    
    

    /*
     * List analytics reports
     */

    public function listAnalysisReportsAction()
    {
        if ($this->__init()) {
            return $this->__init();
        }
        $viewModel = new ViewModel();
        $analysisManager = $this->getAnalysisReportManagerService();

        $userObject = $this->getAuthenticationService()->getIdentity();

        $remainingCredits = $analysisManager->getCreditsAvailable($userObject);
        $key = 'Report expiry notification day';
        $expireDateValue = $analysisManager->getSystemParamValueUsingKey($key);

        $key = 'Report expiry enhancement max day';
        $enhanceExpireDateValue = $analysisManager->getSystemParamValueUsingKey($key);

        $viewModel->setVariable('availableCredits', $remainingCredits);
        $viewModel->setVariable('expireDateValue', $expireDateValue);
        $viewModel->setVariable('enhanceExpireDateValue', $enhanceExpireDateValue);        
    
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/app/analysis-request.js');        
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/app/data-table.js');
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/jquery.dataTables.min.js');
        //$this->getServiceLocator()->get('viewhelpermanager')->get('headLink')->appendStylesheet('/css/jquery.dataTables.css');

        $this->layout()->setTemplate('layout/userAccountLayout');


        return $viewModel;
    }
    
    
    /*
     * List analytics reports
     */

    public function listAnalysisInQueueReportsAction()
    {
        if ($this->__init()) {
            return $this->__init();
        }
        $viewModel = new ViewModel();
        $analysisManager = $this->getAnalysisReportManagerService();

        $userObject = $this->getAuthenticationService()->getIdentity();

        $remainingCredits = $analysisManager->getCreditsAvailable($userObject);
        $key = 'Report expiry notification day';
        $expireDateValue = $analysisManager->getSystemParamValueUsingKey($key);

        $key = 'Report expiry enhancement max day';
        $enhanceExpireDateValue = $analysisManager->getSystemParamValueUsingKey($key);

        $viewModel->setVariable('availableCredits', $remainingCredits);
        $viewModel->setVariable('expireDateValue', $expireDateValue);
        $viewModel->setVariable('enhanceExpireDateValue', $enhanceExpireDateValue);        
        
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/app/analysis-in-queue-request.js');        
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/app/data-table.js');
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/jquery.dataTables.min.js');
        //$this->getServiceLocator()->get('viewhelpermanager')->get('headLink')->appendStylesheet('/css/jquery.dataTables.css');

        $this->layout()->setTemplate('layout/userAccountLayout');


        return $viewModel;
    }    
    
    
    /*
     * Get analytics in queue data
     */
    public function dataAnalysisInQueueAction()
    {
        $paramArray = $this->params()->fromQuery();
        if ($this->__init()) {
            return $this->__init();
        }
        $userObject = $this->getAuthenticationService()->getIdentity();
        $paramArray['user_id'] = $userObject->getId();
        $analysisReportManager = $this->getAnalysisReportManagerService();
        $dataAnalysisInQueue = $analysisReportManager->dataForAnalysisInQueue($paramArray);
        $dataAnalysisInQueue['aaData'] = $this->getDataArrayFromLazyLoadingObject($dataAnalysisInQueue['aaData']);        
        $dataAnalysisInQueue['aaData'] = array_reverse($dataAnalysisInQueue['aaData']);
        $response = new JsonModel($dataAnalysisInQueue);
        return $response;
    }    
    
    
    /*
     * Get analytics reports data
     */

    public function dataAnalysisReportsAction()
    {
        $i = 0;
        $paramArray = $this->params()->fromQuery();

        if ($this->__init()) {
            return $this->__init();
        }
        $userObject = $this->getAuthenticationService()->getIdentity();  
        $paramArray['user_id'] = $userObject->getId();
        $analysisReportManager = $this->getAnalysisReportManagerService();
        $dataAnalysisReport = $analysisReportManager->dataForAnalysisReport($paramArray);
        $dataAnalysisReport['aaData'] = $this->getDataArrayFromLazyLoadingObject($dataAnalysisReport['aaData']);
        
        foreach($dataAnalysisReport['aaData'] as $dataAnalysis){            
            if (!empty($dataAnalysisReport['aaData'][$i][0]['file_expire_dt_tm'])) {
                $fileExpiredtTm = date_format(date_create($dataAnalysisReport['aaData'][$i][0]['file_expire_dt_tm']), "Y-m-d H:i:s");
                $dataAnalysisReport['aaData'][$i][0]['file_expire_dt_tm'] = \Application\Model\Utility::convertUtcToEst($fileExpiredtTm);
            }
            
            if (!empty($dataAnalysisReport['aaData'][$i][0]['file_created_dt_tm'])) {
                $fileCreatedDtTm = date_format(date_create($dataAnalysisReport['aaData'][$i][0]['file_created_dt_tm']), "Y-m-d H:i:s");
                $dataAnalysisReport['aaData'][$i][0]['file_created_dt_tm'] = \Application\Model\Utility::convertUtcToEst($fileCreatedDtTm);            
            }
            
            $i++;            
        }        
        
        $response = new JsonModel($dataAnalysisReport);
        return $response;
    }

    /*
     * Extend expire date 
     */

    public function extendExpireDateAction()
    {
        $request = $this->getRequest();
        $analysisManager = $this->getAnalysisReportManagerService();
        $responseData = $analysisManager->extendAnalysisRequestExpireDate($request->getPost());
        $response = new JsonModel($responseData);
        return $response;
    }

    /*
     * Download analysis request
     */

    public function downloadAnalysisReportAction()
    {
        $request = $this->getRequest();
        $analysisManager = $this->getAnalysisReportManagerService();
        $aws = $this->getServiceLocator()->get('aws');
        $userObject = $this->getAuthenticationService()->getIdentity();  
        $responseData = $analysisManager->downloadAnalysisReport($request->getPost(), $aws, $userObject);
        $response = new JsonModel($responseData);
        return $response;
    }

    /*
     * Download zip folder from server to user system
     */

    public function downloadArZipFolderAction()
    {
        $analysisManager = $this->getAnalysisReportManagerService();
        $response = $analysisManager->downloadArZipFolder();
        return $response;
    }
    
    
    /*
     * Download Excel file from server to user system
     */

    public function downloadArExcelAction()
    {
        $analysisManager = $this->getAnalysisReportManagerService();
        $response = $analysisManager->downloadArExcel();
        return $response;
    }

    /*
     * Extend expire date 
     */

    public function deleteAnalysisRequestAction()
    {
        $request = $this->getRequest();
        $aws = $this->getServiceLocator()->get('aws');
        $userObject = $this->getAuthenticationService()->getIdentity();  
        $analysisManager = $this->getAnalysisReportManagerService();
        $isSystem = false;
        $responseData = $analysisManager->deleteAnalysisRequest($request->getPost(), $aws, $userObject, $isSystem);
        $response = new JsonModel($responseData);
        return $response;
    }

    /*
     * List analytics reports
     */

    public function listExtractsAction()
    {

        if (!$this->getAuthenticationService()->hasIdentity()) {
            return $this->redirect()->toUrl($this->url()->fromRoute('sign-in'));
        }

        $viewModel = new ViewModel();

        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/app/extracts.js');
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/app/data-table.js');
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/jquery.dataTables.min.js');

        $this->layout()->setTemplate('layout/userAccountLayout');


        return $viewModel;
    }

    /*
     * Get analytics reports data
     */

    public function dataExtractsAction()
    {

        $paramArray = $this->params()->fromQuery();
        if (!$this->getAuthenticationService()->hasIdentity()) {
            return $this->redirect()->toUrl($this->url()->fromRoute('sign-in'));
        }
        $userObject = $this->getAuthenticationService()->getIdentity();
        $paramArray['user_id'] = $userObject->getId();
        //$paramArray['parent_user_id'] = $userObject->getParentUserId();
        $analysisReportManager = $this->getAnalysisReportManagerService();
        $dataAnalysisReport = $analysisReportManager->dataForExtracts($paramArray);
        $dataAnalysisReport['aaData'] = $this->getDataArrayFromLazyLoadingObject($dataAnalysisReport['aaData']);
        $response = new JsonModel($dataAnalysisReport);
        return $response;
    }

    /*
     * Extend expire date 
     */

    public function deleteExtractsAction()
    {
        $request = $this->getRequest();
        $aws = $this->getServiceLocator()->get('aws');
        $userObject = $this->getAuthenticationService()->getIdentity();
        $analysisManager = $this->getAnalysisReportManagerService();
        $responseData = $analysisManager->deleteExtracts($request->getPost(), $aws, $userObject);
        $response = new JsonModel($responseData);
        return $response;
    }

    /*
     * Request analysis 
     */

    public function analysisRequestAction()
    {

        if ($this->__init()) {
            return $this->__init();
        }
        $viewModel = new ViewModel();
        $request = $this->getRequest();
        $analysisManager = $this->getAnalysisReportManagerService();
        $userManagerService = $this->getUserManagerService();
        $analysisRequestForm = new \Analytics\Form\AnalysisRequest();

        $userObject = $this->getAuthenticationService()->getIdentity();
        $analysisRequestForm = $analysisManager->populateAnalysisRequestForm($analysisRequestForm, $userObject);
         
        if ($request->isPost()) {
            $postDataArray = $request->getPost()->toArray();
            $response = $analysisManager->analysisRequest($postDataArray, $userObject, $analysisRequestForm);
            
            if ('fail' == $response['status']) {
                $viewModel->setVariable('errorMessage', $response['error_message']);
                $analysisRequestForm = $response['analysis_request_form'];
            } else {
                $message = 'Analysis request has been created successfully';               
                $viewModel->setVariable('successMessage', $message);
            }
        }
        
        /*User credit history*/
        $userCreditHistoryArray = $userManagerService->getUserCreditHistory($userObject);        
        if(!empty($userCreditHistoryArray)){
            $viewModel->setVariable('userCreditHistory', $userCreditHistoryArray);
        }
        
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/app/analysis-request.js');
        $viewModel->setVariable('analysisRequestForm', $analysisRequestForm);
        $this->layout()->setTemplate('layout/userAccountLayout');
        return $viewModel;
    }

}