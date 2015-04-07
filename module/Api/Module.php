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

namespace Api;

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
                'api_manager_service' => function($sm) {
                    $apiManager = new Model\ApiManager();     
                    $configArray = $sm->get('Config');
                    $apiManager->setS3BucketConfiguration($configArray['s3_bucket_configuration']);                    
                    //$apiManager->setJmsSerializerService($sm->get('jms_serializer.serializer'));
                    $serviceList = array('entity_manager','auth_manager_service',
                        'api_service','user_manager_service');
                    $apiManager->setServiceList($serviceList, $sm);
                    return $apiManager;
                },       
                'api_service' => function($sm) {
                    $api = new Model\Api();                   
                    $serviceList = array('entity_manager');
                    $api->setServiceList($serviceList, $sm);
                    return $api;
                },   
            ),
        );
    }

    
}

