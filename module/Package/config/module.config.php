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

namespace Package;

return array(
    'controllers' => array(
        'invokables' => array(
             'Package\Controller\Package' => 'Package\Controller\PackageController',
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
            'pricing' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/package',
                    'defaults' => array(
                        'controller' => 'Package\Controller\Package',
                        'action' => 'price',
                    ),
                ),
            ),     
            'purchase-analysis' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/purchase-analysis',
                    'defaults' => array(
                            'controller' => 'Package\Controller\Package',
                            'action' => 'price',
                    ),
                ),
            ),
            'add-package' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/add-package',
                    'defaults' => array(
                        'controller' => 'Package\Controller\Package',
                        'action' => 'add-package',
                    ),
                ),
            ),    
        ),
    ),      
);
