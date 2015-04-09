<?php
namespace Log;

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
                'log_service' => function($sm) {
                    $logModel = new Model\Log();
                    //$config = \Log\Module::getConfig();
                      $config = $sm->get('Config');
                    //$folderPath = $config['application_log_folder_path'] . '/' . date('Y') . '/' . date('m') . '/' . date('d');
                    $folderPath = $config['application_log_folder_path'] ;  
                    // if folder doesnt exist
                    if (!file_exists($folderPath)) { 
                        // Discard  server umask
                        umask(0);
                        // recursively create folders
                        if (!mkdir($folderPath, 0755, true)) { 
                            throw new \Exception(5002);
                        }
                    }
                    
                    $logModel->setFolderPath($folderPath);
                    $logModel->setLogNameArray($config['log_name']);
                    return $logModel;                     
                }
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}