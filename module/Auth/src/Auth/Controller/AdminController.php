<?php

namespace Auth\Controller;

use Zend\View\Model\ViewModel;
use Zend\Session\Container as SessionContainer;
use Zend\View\Model\JsonModel;

class AdminController extends \Application\Controller\AbstractCoreController
{
    
    public function adminLoginAction(){
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
        $adminSignInForm = new \Auth\Form\AdminLogin();
        $request = $this->getRequest();
        $authenticationManager = $this->getAuthManagerService();      
        
        if ($request->isPost()) {
            $this->getAuthenticationService()->clearIdentity();
            $response = $authenticationManager->authenticate($request->getPost(), $signInForm);
            if ($response['success']) {
                return $this->redirect()->toUrl($this->url()->fromRoute('manage-users'));
            } else {
                $this->setFlashMessage('errorMessage', 'Invalid credentials');                
                return $this->redirect()->toUrl($this->url()->fromRoute('admin-login'));                
            }
        }

        $successMessage = $this->getFlashMessage('successMessage');
        $errorMessage = $this->getFlashMessage('errorMessage');
        
        $this->layout()->setTemplate('layout/adminLayout');        
        if (!empty($errorMessage)) {
            $viewModel->setVariable('errorMessage', $errorMessage);
        }

        if (!empty($successMessage)) {
            $viewModel->setVariable('successMessage', $successMessage);
        }
        
        $viewModel->setVariable('adminSignInForm', $adminSignInForm);
        return $viewModel;        
    }
    
    
    /*
     * Logout user
     */

    public function adminLogoutAction()
    {
        $this->getAuthenticationService()->clearIdentity();
        $roleSession = new SessionContainer('role');
        unset($roleSession->roleName);
        return $this->redirect()->toUrl($this->url()->fromRoute('admin-login'));
    }
    
    
}

?>
