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

namespace Package;

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
                'package_manager_service' => function($sm) {
                    $packageManager = new Model\PackageManager();
                    $packageManager->setJmsSerializerService($sm->get('jms_serializer.serializer'));
                    $serviceList = array('entity_manager', 'package_dao_service',
                        'cart_dao_service','item_dao_service');
                    $packageManager->setServiceList($serviceList, $sm);
                    return $packageManager;
                },
                'package_dao_service' => function($sm) {
                    $packageDao = new Model\PackageDao();
                    $serviceList = array('entity_manager');
                    $packageDao->setServiceList($serviceList, $sm);
                    return $packageDao;
                },
                'package_has_credits_dao_service' => function($sm) {
                    $packageHasCreditsDao = new Model\PackageHasCreditsDao();
                    $serviceList = array('entity_manager');
                    $packageHasCreditsDao->setServiceList($serviceList, $sm);
                    return $packageHasCreditsDao;
                },
            ),
        );
    }

    
}


