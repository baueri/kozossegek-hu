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

    'error_email' => _env('ERROR_EMAIL', 'birkaivan@gmail.com'),
    'contact_email' => _env('CONTACT_EMAIL', 'birkaivan@gmail.com'),
    'sender_email' => _env('SENDER_EMAIL', 'birkaivan@gmail.com'),

    'providers' => [

    ]

];
