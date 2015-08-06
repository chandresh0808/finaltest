<?php

/**
 * AbstractCoreController.php
 * 
 * Common Controller functionality inherited by other controllers
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

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Common Controller functionality inherited by other controllers
 * 
 * @category   Module
 * @package    Review_Module
 * @subpackage ReviewController
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2015 Costrategix
 * @license    http://www.costrategix.com Proprietary 
 * @version    Release: 1.0
 * @link       http://www.costrategix.com
 * 
 */
class AbstractCoreController extends AbstractActionController
{

    /**
     * Get the flash Message
     * 
     * @param string $flashMessageIndex FlashMessageIndexName
     * 
     * @return  Description 
     * 
     * */
    public function getFlashMessage($flashMessageIndex)
    {
        try {
            $flashMessage = $this->flashMessenger()->setNamespace($flashMessageIndex)
                    ->getMessages();
            if ($flashMessage[0]) {
                return $flashMessage[0];
            } else {
                return null;
            }
        } catch (\Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /**
     * Get the current route name
     *
     * @return string - action route name
     */
    protected function _getRouteName()
    {
        return $this->getServiceLocator()
                        ->get('Application')
                        ->getMvcEvent()
                        ->getRouteMatch()
                        ->getMatchedRouteName();
    }

    /**
     * Get cron manager Service instance
     * 
     * @return Cron\Model\CronManager
     */
    public function getCronManagerService()
    {
        return $this->getServiceLocator()->get('cron_manager_service');
    }

    /**
     * Get cron manager Service instance
     * 
     * @return Cron\Model\CronManager
     */
    public function getUserManagerService()
    {
        return $this->getServiceLocator()->get('user_manager_service');
    }

    /*
     * Get application config
     */

    public function getApplicationConfig()
    {

        return $this->getServiceLocator()->get('Config');
    }

    /*
     *  set flash message
     */

    public function setFlashMessage ($name, $message)
    {
        return $this->flashMessenger()
                ->setNamespace($name)
                ->addMessage($message);
    }


    
        
    /*
     * Auth manager service
     */
    
    function getAuthManagerService()
    {
        return $this->getServiceLocator()->get('auth_manager_service');
    }
    
    /*
     * Authentication service instance
     */
    public function getAuthenticationService()
    {
        return $this->getServiceLocator()->get('authentication_service');
    }

    
    /**
     * Method used to render the view-helper methods.
     *
     * @param $helperName
     *
     * @return mixed
     */
    protected function getViewHelper($helperName)
    {
        return $this->getServiceLocator()->get('viewhelpermanager')->get($helperName);
    }
    
    /*
     * Get cookie value 
     */
    public function getCustomCookieValue($name) {
        return $this->getRequest()->getHeaders()->get('Cookie')->$name;
    }
    
    /*
     * Get JMS serializer service instance
     */
    public function getJMSSerializerService() {
        return $this->getServiceLocator()->get('jms_serializer.serializer');
    }
    
    /*
     * Serialize the entity object 
     */
    protected function getDataArrayFromLazyLoadingObject($inputArray, $type="json") {
        $serializer = $this->getServiceLocator()->get('jms_serializer.serializer');
        $context = new \JMS\Serializer\SerializationContext();
        $context->setSerializeNull(true);
        $serializedData = $serializer->serialize($inputArray, $type, $context);
        $serializedData = json_decode($serializedData, true);
        return $serializedData;
    }
    
    /*
     * Get analytic manager service
     */
    public function getAnalysisReportManagerService() {
        return $this->getServiceLocator()->get('analytics_manager_service');
    }
    
    /*
     * Get analytic manager service
     */
    public function getSystemActivityDaoService() {
        return $this->getServiceLocator()->get('system_activity_dao_service');
    }
    
    /*
     * Get analytic manager service
     */
    public function getApplicationManagerService() {
        return $this->getServiceLocator()->get('application_manager_service');
    }
    
}
