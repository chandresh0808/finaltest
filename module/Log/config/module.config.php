<?php
namespace Log;

return array(
   'application_log_folder_path'    => STORAGE_PATH . '/logs',
   'log_name'                       => array (
       'info'    => 'application.log',
       'error'      => 'application.log',
       'notice'    => 'application.log',
       'debug'      => 'application.log',
       'critical'    => 'application.log'
   ), 
);
