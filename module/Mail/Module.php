<?php
namespace Mail;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
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
            'factories' => array(
                'mail_service' => function($sm) {                    
                    $mailModel = new Model\Mail(); 
                    $config = \Mail\Module::getConfig();                    
                    $awsService = $sm->get('aws');                    
                    $mailModel->setAwsService($awsService);
                    $mailModel->setWhiteListEmailArray($config['whiteListEmailArray']);
                    return $mailModel;                     
                },
                'mail_template_service' => function () {
                    $templateModel = new Model\Template();                     
                    return $templateModel;    
                }
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}