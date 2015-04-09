<?php

/**
 * Defines a Api Module configurations
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

namespace Api;

return array(
    
    'controllers' => array(
        'invokables' => array(
            'Api\Controller\Auth' => 'Api\Controller\AuthController',
            'Api\Controller\Utility' => 'Api\Controller\UtilityController',
            'Api\Controller\PreAuth' => 'Api\Controller\PreAuthController',
            'Api\Controller\S3Bucket' => 'Api\Controller\S3BucketController',
            'Api\Controller\RuleBook' => 'Api\Controller\RuleBookController',
            'Api\Controller\Logout' => 'Api\Controller\LogoutController',
            'Api\Controller\ExtractPassword' => 'Api\Controller\ExtractPasswordController',
            'Api\Controller\UserCredit' => 'Api\Controller\UserCreditController',
            'Api\Controller\AnalysisRequest' => 'Api\Controller\AnalysisRequestController',
        ),
    ),
    
 // routing information
    'router' => array(
        'routes' => array(         
            'auth_api' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/api/v1/auth[/:id]',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Api\Controller\Auth',
                    ),
                ),
            ),       
            
            'utility_api' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/api/v1/utility[/:id]',
                    'constraints' => array(
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Api\Controller\Utility',
                    ),
                ),
            ),       
            'pre_auth_api' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/api/v1/preAuth',                    
                    'defaults' => array(
                        'controller' => 'Api\Controller\PreAuth',
                    ),
                ),
            ),     
            's3_backet_config' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/api/v1/s3_credentials',                    
                    'defaults' => array(
                        'controller' => 'Api\Controller\S3Bucket',
                    ),
                ),
            ),     
            'list_rule_book_api' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/api/v1/list_rule_book',                    
                    'defaults' => array(
                        'controller' => 'Api\Controller\RuleBook',
                    ),
                ),
            ),   
            'logout_api' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/api/v1/logout',                    
                    'defaults' => array(
                        'controller' => 'Api\Controller\Logout',
                    ),
                ),
            ),   
            'extract_password_api' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/api/v1/password_for_extract',                    
                    'defaults' => array(
                        'controller' => 'Api\Controller\ExtractPassword',
                    ),
                ),
            ),   
            'user_credit_api' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/api/v1/user_credits',                    
                    'defaults' => array(
                        'controller' => 'Api\Controller\UserCredit',
                    ),
                ),
            ),   
            'analysis_request_api' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/api/v1/analysis_request',                    
                    'defaults' => array(
                        'controller' => 'Api\Controller\AnalysisRequest',
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
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),        
        'template_path_stack' => array(
            'api' => __DIR__ . '/../view',
        ),
    ),
    
    's3_bucket_configuration'   => array(
        'development' => array(
            'bucket_name' => 'dev.wadyaknow',
            'access_key'   => "AKIAIMZZ6D7PBOVYZYQQ",
            'secret_key' => "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"
        ),
        'staging'     => array(
            'bucket_name' => 'staging.wadyaknow',
            'access_key'   => "############################",
            'secret_key' => "############################"
        ),
        'production'  => array(
            'bucket_name' => 'prod.wadyaknow',
            'access_key'   => "@@@@@@@@@@@@@@@@@@@@@@",
            'secret_key' => "@@@@@@@@@@@@@@@@@@@@@@"
        ),
    )
    
);
