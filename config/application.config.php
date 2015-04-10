<?php

require_once __DIR__ . '/autoload/local.php';
use Zend\Stdlib\ArrayUtils;

defined('STORAGE_PATH') || define('STORAGE_PATH', realpath(dirname(__FILE__) . '/../data'));

$applicationEnv = 'development';
// Based on the environment add additional config (like cache)
if (getenv('APPLICATION_ENV')) { // defined in local.php
    $applicationEnv = getenv('APPLICATION_ENV');
}

$config = array(
    // This should be an array of module namespaces used in the application.
    'modules' => array(
        'Application',
        'DoctrineModule',
        'DoctrineORMModule',
        'Api',
        'Auth',
        'User',
        'JMSSerializerModule',
        'Log',
        'Analytics',
        'Cron'
    ),

    // These are various options for the listeners attached to the ModuleManager
    'module_listener_options' => array(
        // This should be an array of paths in which modules reside.
        // If a string key is provided, the listener will consider that a module
        // namespace, the value of that key the specific path to that module's
        // Module class.               
        'module_paths' => array(
            './module',
            './vendor',
        ),

        // An array of paths from which to glob configuration files after
        // modules are loaded. These effectively override configuration
        // provided by modules themselves. Paths may use GLOB_BRACE notation.
        'config_glob_paths' => array(
            //'config/autoload/{,*.}{global,local}.php',
            sprintf('config/autoload/{,*.}{global,%s,local}.php', $applicationEnv),
        ),
    ),
);

$localAppConfigFilename = 'config/application.config.' . $applicationEnv . '.php';
if (is_readable($localAppConfigFilename)) {
    $config = ArrayUtils::merge($config, include $localAppConfigFilename);
}

return $config;