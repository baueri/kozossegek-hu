<?php

return [
    'host' => _env('DB_HOST'),
    'name' => _env('DB_USER'),
    'password' => _env('DB_PASSWORD'),
    'database' => _env('DB_DATABASE'),
    'charset' => _env('DB_CHARSET', 'utf8')
];