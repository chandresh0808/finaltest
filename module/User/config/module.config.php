<?php

/**
 * Defines a User Module configurations
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

namespace User;

return array(
    'controllers' => array(
        'invokables' => array(
             'User\Controller\User' => 'User\Controller\UserController',
             'User\Controller\AdminUser' => 'User\Controller\AdminUserController',
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
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
    ),
    'router' => array(
        'routes' => array(
            'sign-up' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/sign-up',
                    'defaults' => array(
                        'controller' => 'User\Controller\User',
                        'action' => 'sign-up',
                    ),
                ),
            ),
            'activate_user' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/activate[/:activation_code[/]][/:role[/]]',
                    'defaults' => array(
                        'controller' => 'User\Controller\User',
                        'action' => 'activate-user',
                    ),
                ),
            ),
            'user-account' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/user-account',
                    'defaults' => array(
                        'controller' => 'User\Controller\User',
                        'action' => 'user-account',
                    ),
                ),
            ),
            'add-associate-user' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/add-associate-user',
                    'defaults' => array(
                        'controller' => 'User\Controller\User',
                        'action' => 'add-associate-user',
                    ),
                ),
            ),
            'list-associate-user' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/list-associate-user',
                    'defaults' => array(
                        'controller' => 'User\Controller\User',
                        'action' => 'list-associate-user',
                    ),
                ),
            ),
            'delete-associate-user' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/delete-associate-user',
                    'defaults' => array(
                        'controller' => 'User\Controller\User',
                        'action' => 'delete-associate-user',
                    ),
                ),
            ),
            'manage-users' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/manage-users',
                    'defaults' => array(
                        'controller' => 'User\Controller\AdminUser',
                        'action' => 'manage-users',
                    ),
                ),
            ),
            'list-all-users' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/list-all-users',
                    'defaults' => array(
                        'controller' => 'User\Controller\AdminUser',
                        'action' => 'list-all-users',
                    ),
                ),
            ),
            'admin-view-user' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/admin/view-user[/:userId[/]]',
                    'defaults' => array(
                        'controller' => 'User\Controller\AdminUser',
                        'action' => 'view-user',
                    ),
                ),
            ),

            'admin-edit-user' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/admin/edit-user[/:userId[/]]',
                    'defaults' => array(
                        'controller' => 'User\Controller\AdminUser',
                        'action' => 'edit-user',
                    ),
                ),
            ),

            'admin-delete-user' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/admin-delete-user',
                    'defaults' => array(
                        'controller' => 'User\Controller\AdminUser',
                        'action' => 'admin-delete-user',
                    ),
                ),
            ),
            'admin-block-user' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/admin-block-user',
                    'defaults' => array(
                        'controller' => 'User\Controller\AdminUser',
                        'action' => 'admin-block-user',
                    ),
                ),
            ),
            'admin-unblock-user' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/admin-unblock-user',
                    'defaults' => array(
                        'controller' => 'User\Controller\AdminUser',
                        'action' => 'admin-unblock-user',
                    ),
                ),
            ),
                             
            'admin-reset-password' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/admin-reset-password',
                    'defaults' => array(
                        'controller' => 'User\Controller\AdminUser',
                        'action' => 'reset-password',
                    ),
                ),
            ),
            
            'admin-add-custom-package' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/admin/add-custom-package',
                    'defaults' => array(
                        'controller' => 'User\Controller\AdminUser',
                        'action' => 'add-custom-package',
                    ),
                ),
            ),
            
            'admin-expire-custom-package' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/admin/expire-custom-package',
                    'defaults' => array(
                        'controller' => 'User\Controller\AdminUser',
                        'action' => 'expire-custom-package',
                    ),
                ),
            ),
            
            
        ),
    ),

    'captchaKey'   => array(
        'development' => array(
            'pubicKey'   => "6LfAWwYTAAAAAHUyJCtugDqy2biFBMKfPYCnUza2",
            'privateKey' => "6LfAWwYTAAAAAKUCryBLV8MBmwaSh1GY2hJiucw-"
        ),
        'staging'     => array(
            'pubicKey'   => "6LfF1wUTAAAAAO97AwoZ7QS6Ykw8-jf3Sv2mVN0u",
            'privateKey' => "6LfF1wUTAAAAABJ5KeNsdiS7Sbxv2_ZpOQLH2A5F"
        ),
        'production'  => array(
            'pubicKey'   => "6LeppgUTAAAAAJkm44iTWqVg0B6uzDwciN6P74eI",
            'privateKey' => "6LeppgUTAAAAACLcFlQDA534HhJnxYlOS2jrmP9T"
        ),
//        'development' => array(
//            'pubicKey'   => "6LfKPwUTAAAAAMvBFGt0o2ez6TwYrbwa6k23qnip",
//            'privateKey' => "6LfKPwUTAAAAAGJrPAAuAhef1xnECEfrhRIB5Rdo"
//        ),
    ),
    
    
);
