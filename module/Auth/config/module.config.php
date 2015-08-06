<?php

/**
 * Defines a Auth Module configurations
 * 
 * 
 * PHP version 5
 * 
 * @category   Module
 * @package    Auth
 * @subpackage config
 * @author     Costrategix Team <team@costrategix.com>
 * @copyright  2015 CoS
 * @license    http://www.costrategix.com Proprietary 
 * @version    GIT: 1.7
 * @link       http://www.costrategix.com 
 * 
 */


namespace Auth;

return array(
    'controllers' => array(
        'invokables' => array(
            'Auth\Controller\Auth' => 'Auth\Controller\AuthController',
            'Auth\Controller\Admin' => 'Auth\Controller\AdminController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'authentication' => __DIR__ . '/../view',
        ),
    ),
    // Doctrine config
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
        ),
        'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'Application\Entity\User',
                'identity_property' => 'username',
                'credential_property' => 'password'       
                
            ),
        ),
    ),
    'doctrine_factories' => array(
        'authenticationadapter' => 'Auth\Adapter\AdapterFactory',
    ),
    'router' => array(
        'routes' => array(                        
            'sign-in' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/sign-in',
                    'defaults' => array(
                        'controller' => 'Auth\Controller\Auth',
                        'action' => 'login',
                    ),
                ),
            ),
            'sign-out' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/sign-out',
                    'defaults' => array(
                        'controller' => 'Auth\Controller\Auth',
                        'action' => 'logout',
                    ),
                ),
            ),
            'forgot-password' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/forgot-password',
                    'defaults' => array(
                        'controller' => 'Auth\Controller\Auth',
                        'action' => 'forgot-password',
                    ),
                ),
            ),
            
            'set-password' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/set-password[/:reset_code]',
                    'defaults' => array(
                        'controller' => 'Auth\Controller\Auth',
                        'action' => 'reset-password',
                    ),
                ),
            ),
           
            'reset-password' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/reset-password[/:reset_code]',
                    'defaults' => array(
                        'controller' => 'Auth\Controller\Auth',
                        'action' => 'reset-password',
                    ),
                ),
            ),
            
            'admin-login' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/admin',
                    'defaults' => array(
                        'controller' => 'Auth\Controller\Admin',
                        'action' => 'admin-login',
                    ),
                ),
            ),
            
            'admin-logout' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/admin-logout',
                    'defaults' => array(
                        'controller' => 'Auth\Controller\Admin',
                        'action' => 'admin-logout',
                    ),
                ),
            ),
            
        ),
    ),

);
