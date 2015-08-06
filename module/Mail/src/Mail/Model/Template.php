<?php

namespace Mail\Model;

use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;

class Template
{

    protected $_templateDirectory;

    public function __construct()
    {
        $this->_templateDirectory = __DIR__ . '/../../../view/mail/mail';
    }

    /**
     *  Function to get User confirmation mail template.
     * 
     * @param object $userObject information to set in mail Template
     * @return string  $responseString Response message   
     * @throws Base_Exception For message not sent
     * 
     */
    public function getSignUpConfirmationTemplate($userObject, $activationCode = '')
    {
        try {
            $renderer = new PhpRenderer();
            $resolver = new Resolver\AggregateResolver();
            $renderer->setResolver($resolver);
            $map = new Resolver\TemplateMapResolver(array(
                'mail/mail/sign-up-confirmation'
                => $this->_templateDirectory . '/sign-up-confirmation.phtml'
            ));
            $resolver->attach($map);

            $viewModel = new \Zend\View\Model\ViewModel();
            $viewModel->setVariable('user', $userObject);
            $viewModel->setVariable('activationCode', $activationCode);

            $viewModel->setTemplate('mail/mail/sign-up-confirmation');

            $subject = "Audit Companion - Your Account has been created.";
            $responseArray['subject'] = $subject;
            $responseArray['message'] = $renderer->render($viewModel);
            return $responseArray;
        } catch (\Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    
    
    /**
     *  Function to user mail template.
     * 
     * @param object $userObject information to set in mail Template
     * @return string  $responseString Response message   
     * @throws Base_Exception For message not sent
     * 
     */
    public function getUserWelcomeTemplate($userObject)
    {
        try {
            $renderer = new PhpRenderer();
            $resolver = new Resolver\AggregateResolver();
            $renderer->setResolver($resolver);
            $map = new Resolver\TemplateMapResolver(array(
                'mail/mail/user-welcome'
                => $this->_templateDirectory . '/user-welcome.phtml'
            ));
            $resolver->attach($map);

            $viewModel = new \Zend\View\Model\ViewModel();
            $viewModel->setVariable('user', $userObject);          

            $viewModel->setTemplate('mail/mail/user-welcome');

            $subject = "Audit Companion - Welcome.";
            $responseArray['subject'] = $subject;
            $responseArray['message'] = $renderer->render($viewModel);
            return $responseArray;
        } catch (\Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    /* Function to get forgot mail password template.
    * 
    * @param string $firstName        User First Name
    * @param string $passwordResetKey Password Reset Key
    * 
    * @return string  $responseString Response message   
    * @throws Base_Exception For message not sent
    * 
   */ 
   public function getForgotPasswordTemplate ($userObject, $passwordResetKey )
   {     
        $renderer = new PhpRenderer();
        $resolver = new Resolver\AggregateResolver();
        $renderer->setResolver($resolver);
        $map = new Resolver\TemplateMapResolver(array(
            'mail/mail/forgot-password' 
                => $this->_templateDirectory.'/forgot-password.phtml'
        ));
        $resolver->attach($map);

        $viewModel = new \Zend\View\Model\ViewModel();       
        $viewModel->setVariable('user', $userObject);
        $viewModel->setVariable('passwordResetKey', $passwordResetKey);
        $viewModel->setTemplate('mail/mail/forgot-password');       
        
        $subject = "Audit Companion - Password reset requested";            
        $responseArray['subject'] = $subject;
        $responseArray['message'] = $renderer->render($viewModel);
        
        return $responseArray; 
   }
    
   
   /**
     *  Function to get User confirmation mail template.
     * 
     * @param object $userObject information to set in mail Template
     * @return string  $responseString Response message   
     * @throws Base_Exception For message not sent
     * 
     */
    public function getOrderConfirmationTemplate($sendOrderConfirmationMail)
    {
        try {
            $renderer = new PhpRenderer();
            $resolver = new Resolver\AggregateResolver();
            $renderer->setResolver($resolver);
            $map = new Resolver\TemplateMapResolver(array(
                'mail/mail/order-confirmation'
                => $this->_templateDirectory . '/order-confirmation.phtml'
            ));
            $resolver->attach($map);

            $viewModel = new \Zend\View\Model\ViewModel();
            $viewModel->setVariable('orderArray', $sendOrderConfirmationMail);

            $viewModel->setTemplate('mail/mail/order-confirmation');

            $subject = "Audit Companion - Order Confirmation";
            $responseArray['subject'] = $subject;
            $responseArray['message'] = $renderer->render($viewModel);
            return $responseArray;
        } catch (\Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    /**
     *  Function to get User confirmation mail template.
     * 
     * @param object $userObject information to set in mail Template
     * @return string  $responseString Response message   
     * @throws Base_Exception For message not sent
     * 
     */
    public function getAnlysisRequestTemplate($analysisRequestObject, $expiredDay, $userObject, $appBaseLink)
    {
        try {
            $renderer = new PhpRenderer();
            $resolver = new Resolver\AggregateResolver();
            $renderer->setResolver($resolver);
            $map = new Resolver\TemplateMapResolver(array(
                'mail/mail/analysis-request-notification'
                => $this->_templateDirectory . '/analysis-request-notification.phtml'
            ));
            $resolver->attach($map);

            $viewModel = new \Zend\View\Model\ViewModel(); 
            $viewModel->setVariable('analysisRequest', $analysisRequestObject);
            $viewModel->setVariable('expiredDay', $expiredDay);
            $viewModel->setVariable('user', $userObject);
            $viewModel->setVariable('appBaseLink', $appBaseLink);
            
            
            
            $viewModel->setTemplate('mail/mail/analysis-request-notification');

            $subject = "Audit Companion - Analysis Request notification.";
            $responseArray['subject'] = $subject;
            $responseArray['message'] = $renderer->render($viewModel);               
            return $responseArray;           
        } catch (\Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    

    /**
     *  Function to get Request support mail template.
     * 
     * @param array $requestSupportArray information to set in mail Template
     *
     * @return string  $responseString Response message   
     * @throws Base_Exception For message not sent
     * 
     */

    public function getRequestSupportTemplate($userObject, $requestSupportArray)
    {
        try {            
            $renderer = new PhpRenderer();
            $resolver = new Resolver\AggregateResolver();
            $renderer->setResolver($resolver);
            $map = new Resolver\TemplateMapResolver(array(
                'mail/mail/request-support'
                => $this->_templateDirectory . '/request-support.phtml'
            ));
            $resolver->attach($map);

            $viewModel = new \Zend\View\Model\ViewModel();            
            $viewModel->setVariable('requestSupportArray', $requestSupportArray);
            $viewModel->setVariable('user', $userObject);

            $viewModel->setTemplate('mail/mail/request-support');

            $subject = $requestSupportArray['category'];
            $responseArray['subject'] = $subject;
            $responseArray['message'] = $renderer->render($viewModel);
            return $responseArray;
        } catch (\Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
   
}
