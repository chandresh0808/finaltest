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

namespace User;

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
            ),
           'factories' => array(
                'user_manager_service' => function($sm) {
                    $userManager = new Model\UserManager();
                    $serviceList = array('entity_manager','user_session_dao_service',
                        'system_salt_dao_service');
                    $userManager->setServiceList($serviceList, $sm);
                    return $userManager;
                },           
                'user_session_dao_service' => function($sm) {
                    $userSessionDao = new Model\UserSessionDao();
                    $serviceList = array('entity_manager');
                    $userSessionDao->setServiceList($serviceList, $sm);
                    return $userSessionDao;
                },    
                'system_salt_dao_service' => function($sm) {
                    $userHasSaltDao = new Model\SystemSaltDao();
                    $serviceList = array('entity_manager');
                    $userHasSaltDao->setServiceList($serviceList, $sm);
                    return $userHasSaltDao;
                },  
            ),   
        );
    }

    
}


