<?php

namespace User\Controller;

use Zend\View\Model\ViewModel;
use Zend\Session\Container as SessionContainer;
use Zend\View\Model\JsonModel;

class AdminUserController extends \Application\Controller\AbstractCoreController
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
    
    public function manageUsersAction() {
        if ($this->__init()) {
            return $this->__init();
        }
        
        $viewModel = new ViewModel();
        
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/app/admin-user.js');
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/app/data-table.js');
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/jquery.dataTables.min.js');
        
        $this->layout()->setTemplate('layout/adminUserAccountLayout');
        
        return $viewModel;
    }
    
    public function listAllUsersAction() {
        
        if ($this->__init()) {
            return $this->__init();
        }
        $paramArray = $this->params()->fromQuery();
        $userManagerService = $this->getUserManagerService();
        $userData = $userManagerService->dataForUserList($paramArray);        
        $response = new JsonModel($userData); 
        return $response;
    }
    
    /*
     * View user details
     */
    
    public function viewUserAction() {
        if ($this->__init()) {
            return $this->__init();
        }
        $viewModel = new ViewModel();
        $userId = $this->params('userId');
        $userManagerService = $this->getUserManagerService();
        $inputArray['id'] = $userId;
        $userObject = $userManagerService->getUserByParameterList($inputArray);
        
        if (is_object($userObject)) {
            $viewModel->setVariable("userObject", $userObject);
            /*User credit history*/
            $userCreditHistoryArray = $userManagerService->getUserCreditHistory($userObject);        
            if(!empty($userCreditHistoryArray)){
                $viewModel->setVariable('userCreditHistory', $userCreditHistoryArray);
            }  
        }
        
        $successMessage = $this->getFlashMessage('successMessage');
        if (!empty($successMessage)) {
            $viewModel->setVariable('successMessage', $successMessage);
        }
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/jquery-ui-min.js');
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/app/admin-user.js');
        $this->layout()->setTemplate('layout/adminUserAccountLayout');
        return $viewModel;
    }
    
    public function editUserAction() {
        if ($this->__init()) {
            return $this->__init();
        }
        $viewModel = new ViewModel();
        $request = $this->getRequest();
        $userId = $this->params('userId');
        $userAccountForm = new \User\Form\UserAccount();
        $userManagerService = $this->getUserManagerService();
        $inputArray['id'] = $userId;
        $userObject = $userManagerService->getUserByParameterList($inputArray);
        $userAccountForm = $userManagerService->populateUserAccountForm($userAccountForm, $userObject);     
        $viewModel->setVariable("userObject", $userObject);
        
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/jquery.mask.js');
        
        if ($request->isPost()) {
            $postDataArray = $request->getPost()->toArray();
            $response = $userManagerService->updateUserAccount($postDataArray, $userObject, $userAccountForm);

            if ('fail' == $response['status']) {
                $viewModel->setVariable('errorMessage', $response['error_message']);
                $userAccountForm = $response['user_account_form'];
            } else if ('success' == $response['status']) {                               
                $this->setFlashMessage('successMessage', $response['success_message']);
                $this->redirect()->toUrl($this->url()->fromRoute('admin-view-user',array('userId'=>$userId)));
            }
        }
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/jquery-ui-min.js');
        $this->getServiceLocator()->get('viewhelpermanager')->get('headScript')->appendFile('/js/app/admin-user.js');
        /*User credit history*/
        $userCreditHistoryArray = $userManagerService->getUserCreditHistory($userObject);        
        if(!empty($userCreditHistoryArray)){
            $viewModel->setVariable('userCreditHistory', $userCreditHistoryArray);
        }  
        $viewModel->setVariable('userAccountForm', $userAccountForm);
        $this->layout()->setTemplate('layout/adminUserAccountLayout');
        return $viewModel;
    }
    
    
    public function adminDeleteUserAction() {
       if (!$this->getAuthenticationService()->hasIdentity()) {
            $redirectUrl['redirect'] = $this->url()->fromRoute('admin-login');
            $response = new JsonModel($redirectUrl);
            return $response; 
       }
       $this->__init();       
       $postData = $this->getRequest()->getPost();       
       $userManagerService = $this->getUserManagerService();       
       $adminUserData = $userManagerService->adminDeleteUser($postData['admin-delete-user-id']);       
       $response = new JsonModel($adminUserData);
       return $response;
        
    }    
    

    public function adminBlockUserAction() {
       if (!$this->getAuthenticationService()->hasIdentity()) {
            $redirectUrl['redirect'] = $this->url()->fromRoute('admin-login');
            $response = new JsonModel($redirectUrl);
            return $response; 
       }
       $this->__init();       
       $postData = $this->getRequest()->getPost();
       $userManagerService = $this->getUserManagerService();
       $adminUserData = $userManagerService->adminBlockUser($postData['admin-block-user-id']);       
       $response = new JsonModel($adminUserData);
       return $response;
    }

    /*
     * Reset password
     */
    public function resetPasswordAction () {   
        if ($this->__init()) {
            return $this->__init();
        }
        $userManagerService = $this->getUserManagerService();  
        $postData = $this->getRequest()->getPost();       
        $responseData = $userManagerService->sendResetPasswordFromAdmin($postData);
        $response = new JsonModel($responseData);
        return $response;
    }
    
    public function adminUnBlockUserAction() {
       if (!$this->getAuthenticationService()->hasIdentity()) {
            $redirectUrl['redirect'] = $this->url()->fromRoute('sign-in');
            $response = new JsonModel($redirectUrl);
            return $response; 
       }
       $this->__init();
       
       $postData = $this->getRequest()->getPost();
       $userManagerService = $this->getUserManagerService();
       $adminUserData = $userManagerService->adminUnBlockUser($postData['admin-unblock-user-id']);       
       $response = new JsonModel($adminUserData);
       return $response;
       
    }    
    
    /*
     * Add custom package
     */
    public function addCustomPackageAction() {
        
        if ($this->__init()) {
            return $this->__init();
        }
        $userManagerService = $this->getUserManagerService();  
        $postData = $this->getRequest()->getPost();       
        $responseData = $userManagerService->addCustomPackage($postData);
        $response = new JsonModel($responseData);
        return $response;        
    }
    
    /*
     * Add custom package
     */
    public function expireCustomPackageAction() {
        
        if ($this->__init()) {
            return $this->__init();
        }
        $userManagerService = $this->getUserManagerService();  
        $postData = $this->getRequest()->getPost();    
        $loggedInObject = $this->getAuthenticationService()->getIdentity();
        $responseData = $userManagerService->expireCustomPackage($postData, $loggedInObject);
        $response = new JsonModel($responseData);
        return $response;        
    }
    
}
?>
