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

namespace Analytics;

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
                'analytics_manager_service' => function($sm) {
                    $analyticsManager = new Model\AnalyticsManager();
                    $serviceList = array('entity_manager','analysis_request_dao_service');
                    $analyticsManager->setServiceList($serviceList, $sm);
                    return $analyticsManager;
                },           
                'analysis_request_dao_service' => function($sm) {
                    $analysisRequestDao = new Model\AnalysisRequestDao();
                    $serviceList = array('entity_manager');
                    $analysisRequestDao->setServiceList($serviceList, $sm);
                    return $analysisRequestDao;
                },        
            ),   
        );
    }

    
}


