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

namespace Cart;

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
                'cart_manager_service' => function($sm) {
                    $cartManager = new Model\CartManager();      
                    $cartManager->setJmsSerializerService($sm->get('jms_serializer.serializer'));
                    $serviceList = array('entity_manager','cart_dao_service');
                    $cartManager->setServiceList($serviceList, $sm);
                    return $cartManager;
                },  
                'cart_dao_service' => function($sm) {
                    $cartDao = new Model\CartDao();              
                    $serviceList = array('entity_manager');
                    $cartDao->setServiceList($serviceList, $sm);
                    return $cartDao;
                },  
                'item_dao_service' => function($sm) {
                    $itemDao = new Model\ItemDao();              
                    $serviceList = array('entity_manager');
                    $itemDao->setServiceList($serviceList, $sm);
                    return $itemDao;
                }, 
                        
            ),
        );
    }

    
}


