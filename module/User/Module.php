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
                        'system_salt_dao_service','user_dao_service','user_service',
                        'mail_service','mail_template_service','user_has_package_dao_service',
                        'user_credit_history_dao_service','role_dao_service','user_has_role_dao_service', 
                        'system_param_service', 'system_param_dao_service','package_has_credits_dao_service',
                        'system_activity_dao_service');

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
                'user_dao_service' => function($sm) {
                    $userDao = new Model\UserDao();
                    $serviceList = array('entity_manager');
                    $userDao->setServiceList($serviceList, $sm);
                    return $userDao;
                },  
                'user_service' => function($sm) {
                    $user = new Model\User();
                    $serviceList = array('entity_manager','user_dao_service');
                    $user->setServiceList($serviceList, $sm);
                    return $user;
                },  
                'user_has_package_dao_service' => function($sm) {
                    $userHasPackageDao = new Model\UserHasPackageDao();
                    $serviceList = array('entity_manager');
                    $userHasPackageDao->setServiceList($serviceList, $sm);
                    return $userHasPackageDao;
                },  
                'user_credit_history_dao_service' => function($sm) {
                    $userCreditHistoryDao = new Model\UserCreditHistoryDao();
                    $serviceList = array('entity_manager');
                    $userCreditHistoryDao->setServiceList($serviceList, $sm);
                    return $userCreditHistoryDao;
                },  
                'role_dao_service' => function($sm) {
                    $roleDao = new Model\RoleDao();
                    $serviceList = array('entity_manager');
                    $roleDao->setServiceList($serviceList, $sm);
                    return $roleDao;
                },
                'user_has_role_dao_service' => function($sm) {
                    $userHasRoleDao = new Model\UserHasRoleDao();
                    $serviceList = array('entity_manager');
                    $userHasRoleDao->setServiceList($serviceList, $sm);
                    return $userHasRoleDao;
                },

            ),   
        );
    }

    
}


