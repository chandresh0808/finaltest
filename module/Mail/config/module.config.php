<?php
namespace Mail;
// checking for enviroment
$whiteListEmailArray = array();
$env = getenv('APPLICATION_ENV');
if ($env != "production") {
    $whiteListEmailArray = array(                         
        'harish.ms@costrategix.com',                                
        'khalander.muhammed@costrategix.com'   
    );
}

return array(
    'view_manager' => array(        
        'template_path_stack' => array(
            __DIR__ . '/../view',
        )
    ),
     'whiteListEmailArray' => $whiteListEmailArray 
    );