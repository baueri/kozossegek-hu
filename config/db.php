<?php

return [
    'host' => env('DB_HOST'),
    'user' => env('DB_USER'),
    'password' => env('DB_PASSWORD'),
    'database' => env('DB_NAME'),
    'charset' => env('DB_CHARSET', 'utf8'),
    'port' => env('DB_PORT', '3306')
];