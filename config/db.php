<?php

return [
    'host' => _env('DB_HOST'),
    'user' => _env('DB_USER'),
    'password' => _env('DB_PASSWORD'),
    'database' => _env('DB_NAME'),
    'charset' => _env('DB_CHARSET', 'utf8'),
    'port' => _env('DB_PORT', '3306')
];