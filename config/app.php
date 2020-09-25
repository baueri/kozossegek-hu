<?php
return [
    'environment' => _env('ENVIRONMENT', 'development'),
    'base_auth' => _env('BASE_AUTH', false),
    'base_auth.user' => 'kozossegek',
    'base_auth.password' => '***REMOVED***',
    'docache' => _env('ENVIRONMENT') != 'production',
    'sanitize' => _env('SANITIZE_OUTPUT', false),
];