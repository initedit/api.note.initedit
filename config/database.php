<?php

return [
    'default' => env('DB_CONNECTION', 'mysql_slave'),
    'migrations' => 'migrations',
    'connections' => [
        'mysql_slave' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST','127.0.0.1'),
            'port' => env('DB_PORT','3306'),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
        ],
        'mysql_master' => [
            'driver' => 'mysql',
            'host' => env('DB2_HOST','127.0.0.1'),
            'port' => env('DB_PORT','3306'),
            'database' => env('DB2_DATABASE'),
            'username' => env('DB2_USERNAME'),
            'password' => env('DB2_PASSWORD'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'strict' => false,
        ],
    ],
];
