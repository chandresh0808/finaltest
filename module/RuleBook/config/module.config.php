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


namespace RuleBook;

return array(
    'controllers' => array(
        'invokables' => array(
            'RuleBook\Controller\RuleBook' => 'RuleBook\Controller\RuleBookController',
            'RuleBook\Controller\ModifyRuleBook' => 'RuleBook\Controller\ModifyRuleBookController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'rulebook' => __DIR__ . '/../view',
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
            'rulebook-list' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/rulebook-list',
                    'defaults' => array(
                        'controller' => 'RuleBook\Controller\RuleBook',
                        'action' => 'list-rule-books',
                    ),
                ),
            ),
            'rulebook-data' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/rulebook-data',
                    'defaults' => array(
                        'controller' => 'RuleBook\Controller\RuleBook',
                        'action' => 'rule-book-data',
                    ),
                ),
            ),
            'rulebook-delete' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/rulebook-delete',
                    'defaults' => array(
                        'controller' => 'RuleBook\Controller\RuleBook',
                        'action' => 'rule-book-data-delete',
                    ),
                ),
            ),            
            'rulebook-copy' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/rulebook-copy',
                    'defaults' => array(
                        'controller' => 'RuleBook\Controller\RuleBook',
                        'action' => 'rule-book-data-copy',
                    ),
                ),
            ),
            'upload-excel' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/upload-excel',
                    'defaults' => array(
                    'controller'=>'RuleBook\Controller\RuleBook',
                        'action' => 'upload-excel-file'
                    ),
                ),   
            ),
            'download-excel' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/download-excel[/:downloadparameter]',
                    'defaults' => array(
                    'controller'=>'RuleBook\Controller\RuleBook',
                        'action' => 'download-excel-file'
                    ),
                ),   
            ),
            'download-utility' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/download-utility[/:downloadparameter]',
                    'defaults' => array(
                        'controller' => 'RuleBook\Controller\RuleBook',
                        'action' => 'download-utility-file',
                    ),
                ),
            ),
            'modify-rulebook' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/modify-rulebook[/:modifyparameter]',
                    'defaults' => array(
                        'controller' => 'RuleBook\Controller\ModifyRuleBook',
                        'action' => 'modify-rulebook',
                    ),
                ),
            ),
            'on-select-job-function-list' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/on-select-job-function-list',
                    'defaults' => array(
                        'controller' => 'RuleBook\Controller\ModifyRuleBook',
                        'action' => 'on-select-job-function-list',
                    ),
                ),
            ),
            'on-select-transaction-list' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/on-select-transaction-list',
                    'defaults' => array(
                        'controller' => 'RuleBook\Controller\ModifyRuleBook',
                        'action' => 'on-select-transaction-list',
                    ),
                ),
            ),
            'add-risk' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/add-risk',
                    'defaults' => array(
                        'controller' => 'RuleBook\Controller\ModifyRuleBook',
                        'action' => 'add-risk',
                    ),
                ),
            ),
            'add-job-function' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/add-job-function',
                    'defaults' => array(
                        'controller' => 'RuleBook\Controller\ModifyRuleBook',
                        'action' => 'add-job-function',
                    ),
                ),
            ),
            'add-transaction' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/add-transaction',
                    'defaults' => array(
                        'controller' => 'RuleBook\Controller\ModifyRuleBook',
                        'action' => 'add-transaction',
                    ),
                ),
            ),
            'delete-risk' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/delete-risk',
                    'defaults' => array(
                        'controller' => 'RuleBook\Controller\ModifyRuleBook',
                        'action' => 'delete-risk',
                    ),
                ),
            ),
            'delete-job-function' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/delete-job-function',
                    'defaults' => array(
                        'controller' => 'RuleBook\Controller\ModifyRuleBook',
                        'action' => 'delete-job-function',
                    ),
                ),
            ),
            'delete-transaction' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/delete-transaction',
                    'defaults' => array(
                        'controller' => 'RuleBook\Controller\ModifyRuleBook',
                        'action' => 'delete-transaction',
                    ),
                ),
            ),
            'edit-risk' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/edit-risk',
                    'defaults' => array(
                        'controller' => 'RuleBook\Controller\ModifyRuleBook',
                        'action' => 'edit-risk',
                    ),
                ),
            ),
            'edit-job-function' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/edit-job-function',
                    'defaults' => array(
                        'controller' => 'RuleBook\Controller\ModifyRuleBook',
                        'action' => 'edit-job-function',
                    ),
                ),
            ),
            'edit-transaction' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/edit-transaction',
                    'defaults' => array(
                        'controller' => 'RuleBook\Controller\ModifyRuleBook',
                        'action' => 'edit-transaction',
                    ),
                ),
            ),
            'edit-rulebook' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/edit-rulebook',
                    'defaults' => array(
                        'controller' => 'RuleBook\Controller\ModifyRuleBook',
                        'action' => 'edit-rulebook',
                    ),
                ),
            ),
            'complete-rule-book' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/complete-rule-book[/:previousId]',
                    'defaults' => array(
                        'controller' => 'RuleBook\Controller\RuleBook',
                        'action' => 'complete-rule-book',
                    ),
                ),
            ), 
            'default-job-function-list' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/default-job-function-list',
                    'defaults' => array(
                        'controller' => 'RuleBook\Controller\ModifyRuleBook',
                        'action' => 'default-job-function-list',
                    ),
                ),
            ), 
            'default-transaction-list' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/default-transaction-list',
                    'defaults' => array(
                        'controller' => 'RuleBook\Controller\ModifyRuleBook',
                        'action' => 'default-transaction-list',
                    ),
                ),
            ), 
        ),
    ),

);
