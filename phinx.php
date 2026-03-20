<?php

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => env('environment', 'local'),
        'local' => [
            'adapter' => 'mysql',
            'host' => env('DB_HOST'),
            'name' => env('DB_NAME'),
            'user' => env('DB_USER'),
            'pass' => env('DB_PASSWORD'),
            'port' => '3306',
            'charset' => 'utf8',
        ],
        'production' => [
            'adapter' => 'mysql',
            'host' => env('DB_HOST'),
            'name' => env('DB_NAME'),
            'user' => env('DB_USER'),
            'pass' => env('DB_PASSWORD'),
            'port' => '3306',
            'charset' => 'utf8',
        ],
    ],
    'version_order' => 'creation'
];
