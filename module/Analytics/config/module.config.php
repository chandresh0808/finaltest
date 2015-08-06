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


namespace Analytics;

return array(
    'controllers' => array(
        'invokables' => array(
            'Analytics\Controller\Analytics' => 'Analytics\Controller\AnalyticsController',
            'Analytics\Controller\AnalyticsRequestSupport' => 'Analytics\Controller\AnalyticsRequestSupportController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'analytics' => __DIR__ . '/../view',
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
            'analysis-reports' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/analysis-reports',
                    'defaults' => array(
                        'controller' => 'Analytics\Controller\Analytics',
                        'action' => 'list-analysis-reports',
                    ),
                ),
            ),
            'analysis-in-queue-reports' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/analysis-in-queue-reports',
                    'defaults' => array(
                        'controller' => 'Analytics\Controller\Analytics',
                        'action' => 'list-analysis-in-queue-reports',
                    ),
                ),
            ),
            'data-analysis-reports' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/data-analysis-reports',
                    'defaults' => array(
                        'controller' => 'Analytics\Controller\Analytics',
                        'action' => 'data-analysis-reports',
                    ),
                ),
            ),
            'data-analysis-in-queue' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/data-analysis-in-queue',
                    'defaults' => array(
                        'controller' => 'Analytics\Controller\Analytics',
                        'action' => 'data-analysis-in-queue',
                    ),
                ),
            ),
            'extend-analysis-reports' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/extend-analysis-reports',
                    'defaults' => array(
                        'controller' => 'Analytics\Controller\Analytics',
                        'action' => 'extend-expire-date',
                    ),
                ),
            ),            
            'download-analysis-reports' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/download-analysis-reports',
                    'defaults' => array(
                        'controller' => 'Analytics\Controller\Analytics',
                        'action' => 'download-analysis-report',
                    ),
                ),
            ),
            'download-ar-zip-folder' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/download-ar-zip-folder',
                    'defaults' => array(
                        'controller' => 'Analytics\Controller\Analytics',
                        'action' => 'download-ar-zip-folder',
                    ),
                ),
            ),
            
            'delete-analysis-reports' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/delete-analysis-reports',
                    'defaults' => array(
                        'controller' => 'Analytics\Controller\Analytics',
                        'action' => 'delete-analysis-request',
                    ),
                ),
            ),
            
            'request-support' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/request-support',
                    'defaults' => array(
                        'controller' => 'Analytics\Controller\AnalyticsRequestSupport',
                        'action' => 'request-support',
                    ),
                ),
            ),    
            'list-extracts' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/list-extracts',
                    'defaults' => array(
                        'controller' => 'Analytics\Controller\Analytics',
                        'action' => 'list-extracts',
                    ),
                ),
            ),              
            'data-list-extracts' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/data-list-extracts',
                    'defaults' => array(
                        'controller' => 'Analytics\Controller\Analytics',
                        'action' => 'data-extracts',
                    ),
                ),
            ), 
            'delete_extract_url' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/delete_extract_url',
                    'defaults' => array(
                        'controller' => 'Analytics\Controller\Analytics',
                        'action' => 'delete-extracts',
                    ),
                ),
            ), 
            'analysis-request' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/analysis-request',
                    'defaults' => array(
                        'controller' => 'Analytics\Controller\Analytics',
                        'action' => 'analysis-request',
                    ),
                ),
            ),
            'download-ar-excel' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/download-ar-excel',
                    'defaults' => array(
                        'controller' => 'Analytics\Controller\Analytics',
                        'action' => 'download-ar-excel',
                    ),
                ),
            ),
        ),
    ),

);
