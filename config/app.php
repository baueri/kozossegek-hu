<?php
return [
    'environment' => _env('ENVIRONMENT', 'development'),
    'base_auth' => _env('BASE_AUTH', false),
    'base_auth.user' => 'kozossegek',
    'base_auth.password' => '***REMOVED***',
    'docache' => _env('ENVIRONMENT') != 'production',
    'sanitize' => _env('SANITIZE_OUTPUT', false),
    'debug' => _env('DEBUG', false),
    'coming_soon' => _env('COMING_SOON', false),

    'email' => _env('EMAIL'),
    'email_password' => _env('EMAIL_PASSWORD'),
    'email_host' => _env('EMAIL_HOST'),
    'email_port' => _env('EMAIL_PORT'),
    'email_ssl' => _env('EMAIL_SSL'),

    'providers' => [

    ]

];
