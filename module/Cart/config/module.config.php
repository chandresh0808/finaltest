<?php

/**
 * Defines a Package Module configurations
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

namespace Cart;

return array(
    'controllers' => array(
        'invokables' => array(
             'Cart\Controller\Cart' => 'Cart\Controller\CartController',
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
            'display-cart' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/cart[/:flag]',
                    'defaults' => array(
                        'controller' => 'Cart\Controller\Cart',
                        'action' => 'display-cart',
                    ),
                ),
            ),        
            'delete-cart-item' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/delete-cart-item',
                    'defaults' => array(
                        'controller' => 'Cart\Controller\Cart',
                        'action' => 'delete-cart-item',
                    ),
                ),
            ), 
        ),
    ),
        
);
