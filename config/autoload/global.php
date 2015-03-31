<?php
return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => DB_HOST,
                    'port'     => DB_PORT,
                    'dbname'   => DB_NAME,
                    'user'     => DB_USER_NAME,
                    'password' => DB_PASSWORD,
                    
                )
            )
        ),        
    ),
);














