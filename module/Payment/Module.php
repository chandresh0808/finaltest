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

namespace Payment;

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
                'payment_manager_service' => function($sm) {
                    $paymentManager = new Model\PaymentManager();     
                    $configArray = $sm->get('Config');
                    $paymentManager->setJmsSerializerService($sm->get('jms_serializer.serializer'));
                    $paymentManager->setPaypalConfig($configArray['speck-paypal-api']);
                    $serviceList = array('entity_manager', 'user_manager_service','order_dao_service',
                        'payment_method_dao_service','order_details_dao_service','payment_service',
                        'payment_history_dao_service','cart_dao_service','mail_service','mail_template_service',
                        'log_service', 'system_param_service', 'system_param_dao_service','system_activity_dao_service'
                       );
                    $paymentManager->setServiceList($serviceList, $sm);
                    return $paymentManager;
                },
                'order_dao_service' => function($sm) {
                    $orderDaoService = new Model\OrderDao();                   
                    $serviceList = array('entity_manager');
                    $orderDaoService->setServiceList($serviceList, $sm);
                    return $orderDaoService;
                },
                'order_details_dao_service' => function($sm) {
                    $orderDetailsDaoService = new Model\OrderDetailsDao();                   
                    $serviceList = array('entity_manager');
                    $orderDetailsDaoService->setServiceList($serviceList, $sm);
                    return $orderDetailsDaoService;
                },
                'payment_method_dao_service' => function($sm) {
                    $orderDetailsDaoService = new Model\PaymentMethodDao();                   
                    $serviceList = array('entity_manager');
                    $orderDetailsDaoService->setServiceList($serviceList, $sm);
                    return $orderDetailsDaoService;
                },
                'payment_service' => function($sm) {
                    $orderDetailsDaoService = new Model\Payment();                   
                    $serviceList = array('entity_manager');
                    $orderDetailsDaoService->setServiceList($serviceList, $sm);
                    return $orderDetailsDaoService;
                },
                'payment_history_dao_service' => function($sm) {
                    $paymentHistoryDaoService = new Model\PaymentHistoryDao();                   
                    $serviceList = array('entity_manager');
                    $paymentHistoryDaoService->setServiceList($serviceList, $sm);
                    return $paymentHistoryDaoService;
                },
            ),
        );
    }

    
}


