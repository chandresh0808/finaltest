<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        /* Log APi request */
      /*  $e->getApplication()->getEventManager()->getSharedManager()->attach(
                'Zend\Mvc\Controller\AbstractRestfulController', 'dispatch', array(new \Api\Model\Api, 'logApiRequest'), 100
        );
      */
        /* Log APi Response */
        //$eventManager->attach(MvcEvent::EVENT_FINISH, array('\Api\Model\Api', 'logApiResponse'), 1);
        
        /* Register a render event */
        $app = $e->getParam('application');
        $app->getEventManager()->attach('render', array(new \Application\View\Helper\Layout, 'setLayoutTitle'));
        
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
            'factories' => array(
                // Doctine
                'entity_manager' => function($sm) {
                    return $sm->get('doctrine.entitymanager.orm_default');
                },
                'system_param_service' => function($sm) {
                    $systemParamObject = new Model\SystemParam();
                    $serviceList = array('entity_manager', 'system_param_dao_service');
                    $systemParamObject->setServiceList($serviceList, $sm);
                    return $systemParamObject;
                },
                'system_param_dao_service' => function($sm) {
                    $systemParamDao = new Model\SystemParamDao();
                    $serviceList = array('entity_manager');
                    $systemParamDao->setServiceList($serviceList, $sm);
                    return $systemParamDao;
                },
                'activity_dao_service' => function($sm) {
                    $activityDao = new Model\ActivityDao();
                    $serviceList = array('entity_manager');
                    $activityDao->setServiceList($serviceList, $sm);
                    return $activityDao;
                },
                'system_activity_dao_service' => function($sm) {
                    $systemActivityDao = new Model\SystemActivityDao();
                    $serviceList = array('entity_manager','activity_dao_service');
                    $systemActivityDao->setServiceList($serviceList, $sm);
                    return $systemActivityDao;
                },
                'application_manager_service' => function($sm) {
                    $applicationManager = new Model\ApplicationManager();
                    $serviceList = array('entity_manager','system_activity_dao_service');
                    $applicationManager->setServiceList($serviceList, $sm);
                    return $applicationManager;
                },
            ),
            

        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'application_config_helper' => function($sm) {
                    $helper = new \Application\View\Helper\ApplicationConfig($sm->getServiceLocator()->get('Config'));
                    return $helper;
                },
                'authenticate_view_helper' => function($sm) {
                    $helper = new \Application\View\Helper\GetIdentity($sm->getServiceLocator()->get('authentication_service'));
                    return $helper;
                },
                'mobile_detect_view_helper' => function($sm) {
                    $helper = new \Application\View\Helper\MobileDetect();
                    return $helper;
                }
            )
        );
    }

}
