<?php

/**
 * Defines a Application Module configurations
 * 
 * PHP version 5
 * 
 * @category   Module
 * @package    Application
 * @subpackage Congif
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2015 CoS
 * @license    http://www.costrategix.com Proprietary 
 * @version    GIT: 1.7
 * @link       http://www.costrategix.com 
 * 
 */

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Auth\Controller\Auth',
                        'action' => 'login',
                    ),
                ),
            ),
            'list-system-activity' => array(
                    'type' => 'segment',
                    'options' => array(
                        'route' => '/list-system-activity',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Admin',
                            'action' => 'list-system-activity',
                        ),
                    ),
                ),
            'data-system-activity' => array(
                    'type' => 'segment',
                    'options' => array(
                        'route' => '/data-system-activity',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Admin',
                            'action' => 'data-system-activity',
                        ),
                    ),
                ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),

    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Admin' => 'Application\Controller\AdminController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'base_path' => '/',
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',           
            'layout/cmsLayout' => __DIR__ . '/../view/layout/cmsLayout/layout.phtml',
            'layout/adminLayout' => __DIR__ . '/../view/layout/adminLayout/sign-in-layout.phtml',
            'layout/userAccountLayout' => __DIR__ . '/../view/layout/user-account-layout.phtml',
            'layout/adminUserAccountLayout' => __DIR__ . '/../view/layout/admin-user-account-layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),


    // Will generate doctrine entities in application module
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ), 
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),
    'cmsBaseUrl' => array(
        'development' => "http://auditcompanion.dev0.cosdevx.com",
        'staging' => "https://staging.auditcompanion.biz" ,   
        'production' => "https://auditcompanion.biz" 
    ),
    'appBaseUrl' => array(
        'development' => "http://app.auditcompanion.dev0.cosdevx.com",
        'staging' => "https://app.staging.auditcompanion.biz" ,   
        'production' => "https://app.auditcompanion.biz" 
    ),
    'DMS_url' => array('about_us' => "https://staging.auditcompanion.biz/about/"),
);
