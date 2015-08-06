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

namespace RuleBook\Controller;

use Zend\View\Model\ViewModel;
use Zend\Session\Container as SessionContainer;
use Zend\View\Model\JsonModel;
use Application\View\Helper\MobileDetect;
use Application\Model\Constant as Constant;

class RuleBookController extends \Application\Controller\AbstractCoreController
{

    /**
     *
     * common function like constructor
     */
    public function __init()
    {
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
     * List Rulebooks for the particular user
     */

    public function listRuleBooksAction()
    {

        if ($this->__init()) {
            return $this->__init();
        }
        
        $viewModel = new ViewModel();
        $ruleBookManager = $this->getRuleBookService();
        $ruleBookData = $ruleBookManager->getRuleBookObj(1);
        $lastUpdatedDateTimeObj = $ruleBookData->getUpdatedDtTm();
        $analysisRequestListObj = $ruleBookData->getAnalysisRequestList();

        $viewModel->setVariable('lastUpdatedRuleBook', $lastUpdatedDateTimeObj->format('m/d/Y'));
        $viewModel->setVariable('ruleBookDescription', $ruleBookData->getDescription());
        $viewModel->setVariable('ruleBookName', $ruleBookData->getName());

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

        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/jquery.form.js');
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/app/rulebook-list.js');
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/app/data-table.js');
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/jquery.dataTables.min.js');


        $this->layout()->setTemplate('layout/userAccountLayout');

        return $viewModel;
    }

    /*
     * Get rulebook data
     */

    public function ruleBookDataAction()
    {
        $paramArray = $this->params()->fromQuery();

        if ($this->__init()) {
            return $this->__init();
        }
        
        $userObject = $this->getAuthenticationService()->getIdentity();
        $paramArray['user_id'] = array(0, $userObject->getId());
        $ruleBookManager = $this->getRuleBookService();
        $ruleBookData = $ruleBookManager->dataForRuleBookList($paramArray);
        $ruleBookData['aaData'] = $this->getDataArrayFromLazyLoadingObject($ruleBookData['aaData']);
        $response = new JsonModel($ruleBookData);
        return $response;
    }

    /*
     * copy rulebook data
     */

    public function ruleBookDataCopyAction()
    {
        if (!$this->getAuthenticationService()->hasIdentity()) {
            $redirectUrl['redirect'] = $this->url()->fromRoute('sign-in');
            $response = new JsonModel($redirectUrl);
            return $response;            
        }        
        $this->__init();
        
        $request = $this->getRequest();
        $ruleBookManager = $this->getRuleBookService();
        $userObject = $this->getAuthenticationService()->getIdentity();
        $userId = $userObject->getId();
        $responseData = $ruleBookManager->createRulebookCopy($request->getPost(), $userId);
        $response = new JsonModel($responseData);
        return $response;
    }

    /*
     * Delete rulebook data
     */

    public function ruleBookDataDeleteAction()
    {
        if (!$this->getAuthenticationService()->hasIdentity()) {
            $redirectUrl['redirect'] = $this->url()->fromRoute('sign-in');
            $response = new JsonModel($redirectUrl);
            return $response; 
        }
        $this->__init();
                
        $request = $this->getRequest();
        $userObject = $this->getAuthenticationService()->getIdentity();
        $ruleBookManager = $this->getRuleBookService();
        $responseData = $ruleBookManager->setRuleBookDeleteFlag($request->getPost(), $userObject);
        $response = new JsonModel($responseData);
        return $response;
    }

    /**
     * Upload rule book excel file
     * @return \Zend\View\Model\ViewModel
     */
    public function uploadExcelFileAction()
    {
        if ($this->__init()) {
            return $this->__init();
        }
        
        $inputFileArray = $_FILES["file_rule_book_excel"];

        $userObject = $this->getAuthenticationService()->getIdentity();
        $analysisManager = $this->getRuleBookService();
        $responseArray = $analysisManager->uploadRuleBookExcelInToDb($userObject, $inputFileArray);
        $response = new JsonModel($responseArray);
        return $response;
    }

    public function downloadExcelFileAction()
    {
        if ($this->__init()) {
            return $this->__init();
        }
                
        $request = $this->getRequest();        
        
        $userObject = $this->getAuthenticationService()->getIdentity();
        $downloadParameter = $this->params('downloadparameter');

        $response = array();
        if (preg_match('/^\d+$/', $downloadParameter)) {
            $downloadParameter = intval($this->params('downloadparameter'));

            if ($downloadParameter != '' && is_int($downloadParameter)) {
                $analysisManager = $this->getRuleBookService();
                $response = $analysisManager->downloadRuleBookExcelFromToDb($downloadParameter, $userObject);
            }
        } else {
            return $this->redirect()->toUrl($this->url()->fromRoute('rulebook-list'));
        }
    }

    /**
     * download utility
     */
    public function downloadUtilityFileAction()
    {
        $roleSession = new SessionContainer('role');
        if (!$this->getAuthenticationService()->hasIdentity()) {
            return $this->redirect()->toUrl($this->url()->fromRoute('sign-in'));
        } elseif ($roleSession->roleName == 'admin') {
            return $this->redirect()->toUrl($this->url()->fromRoute('manage-users'));
        }

        $viewModel = new ViewModel();
        $analysisManager = $this->getAnalysisReportManagerService();

        $key = 'download_utility';
        $downloadUtilityJsonData = $analysisManager->getSystemParamValueUsingKey($key);
        $downloadUtilityParsedJsonData = json_decode($downloadUtilityJsonData, true);
        $viewModel->setVariable("downloadUtilityParsedJsonData", $downloadUtilityParsedJsonData);

        $downloadParameter = $this->params('downloadparameter');
        $this->layout()->setTemplate('layout/userAccountLayout');

        if ($downloadParameter != "" && $downloadParameter == 'download') {

            $applicationEnv = getenv('APPLICATION_ENV');

            $exeFilePath = "/../public/" . $applicationEnv . '/' . $downloadUtilityParsedJsonData['fileName'];

            if (!file_exists(APPLICATION_PATH . $exeFilePath)) {
                $viewModel->setVariable("errorFlag", 1);
            } else {

                /* system activity log */
                $userObject = $this->getAuthenticationService()->getIdentity();
                $systemActivityDaoService = $this->getSystemActivityDaoService();
                $code = Constant::ACTIVITY_CODE_DA;
                $userId = $userObject->getId();
                $fullName = $userObject->getFirstName() . " " . $userObject->getLastName();
                $comment = "{$fullName} has downloaded utility";
                $systemActivityObject = $systemActivityDaoService->createSystemActivityLog($code, $userId, $comment);

                header('Content-Description: File Transfer');
                header("Content-Type: application/force-download");
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename=' . basename(APPLICATION_PATH . $exeFilePath));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-Length: ' . filesize(APPLICATION_PATH . $exeFilePath));
                ob_clean();
                flush();
                readfile(APPLICATION_PATH . $exeFilePath);
            }
        }

        return $viewModel;
    }

    /*
     * Complete rulebook
     */

    public function completeRuleBookAction()
    {
        if ($this->__init()) {
            return $this->__init();
        }
        
        $previousId = $this->params('previousId');
        ;
        $viewModel = new ViewModel();
        $viewModel->setVariable('previousId', $previousId);
        $this->layout()->setTemplate('layout/userAccountLayout');
        return $viewModel;
    }

}