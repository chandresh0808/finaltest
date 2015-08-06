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

namespace Payment;

return array(
    'controllers' => array(
        'invokables' => array(
             'Payment\Controller\Payment' => 'Payment\Controller\PaymentController',
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
            'checkout' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/checkout[/:order_id][/:cart_id]',
                    'defaults' => array(
                        'controller' => 'Payment\Controller\Payment',
                        'action' => 'checkout',
                    ),
                ),
            ),
            'confirm-order' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/confirm-order[/:order_id][/:cart_id]',
                    'defaults' => array(
                        'controller' => 'Payment\Controller\Payment',
                        'action' => 'confirm-order',
                    ),
                ),
            ), 
            
            'thank-you' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/thank-you[/:order_id]',
                    'defaults' => array(
                        'controller' => 'Payment\Controller\Payment',
                        'action' => 'thank-you',
                    ),
                ),
            ), 
        ),
    ),      
);
