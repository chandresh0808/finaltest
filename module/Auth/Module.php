<?php

/**
 * Module configurations
 * 
 * PHP version 5
 * 
 * @category   Module
 * @package    Api
 * @subpackage Congif
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2015 CoS
 * @license    http://www.costrategix.com Proprietary 
 * @version    GIT: 1.7
 * @link       http://www.costrategix.com 
 * 
 */

namespace Auth;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    
    public function getServiceConfig()
    {
        return array(            
            'invokables' => array(       
                  'user_login_form' => 'Auth\Form\Login',
            ),
           'factories' => array(
                'zfcuser_auth_service' => function ($serviceManager) {
                    return  $authenticationService = $serviceManager->get('doctrine.authenticationservice.orm_default');
                },
                'auth_manager_service' => function($sm) {
                    $authManager = new Model\AuthManager();
                    $serviceList = array('entity_manager','user_manager_service',
                        'authentication_service','auth_service','auth_dao_service',
                        'mail_service','mail_template_service', 'system_param_service', 'system_param_dao_service');
                    $authManager->setServiceList($serviceList, $sm);
                    return $authManager;
                },                   
                'authentication_service' => function($sm) {
                    $object = new Model\AuthenticationService();
                    $object->setAuthenticationAdapterService($sm->get('zfcuser_auth_service'));
                    return $object;
                },
                'auth_service' => function($sm) {
                    $object = new Model\Auth();
                    $serviceList = array('entity_manager');
                    $object->setServiceList($serviceList, $sm);
                    return $object;
                },
                'auth_dao_service' => function($sm) {
                    $authDao = new Model\AuthDao();
                    $serviceList = array('entity_manager');
                    $authDao->setServiceList($serviceList, $sm);
                    return $authDao;
                },    
            ),   
        );
    }

    
}


