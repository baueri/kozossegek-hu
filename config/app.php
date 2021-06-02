<?php

return [
    'site_url' => _env('SITE_URL'),
    'site_name' => _env('SITE_NAME', 'kozossegek.hu'),
    'environment' => _env('ENVIRONMENT', 'development'),
    'base_auth' => _env('BASE_AUTH', false),
    'base_auth.user' => 'kozossegek',
    'base_auth.password' => '***REMOVED***',
    'docache' => _env('ENVIRONMENT') != 'production',
    'sanitize' => _env('SANITIZE_OUTPUT', false),
    'debug' => _env('DEBUG', false),
    'coming_soon' => _env('COMING_SOON', false),

    'email' => _env('EMAIL_ADDRESS'),
    'email_password' => _env('EMAIL_PASSWORD'),
    'email_host' => _env('EMAIL_HOST'),
    'email_port' => _env('EMAIL_PORT'),
    'email_ssl' => _env('EMAIL_SSL'),

    'contact_email' => _env('CONTACT_EMAIL', 'ivan.bauer90@gmail.com'),
    'error_email' => _env('ERROR_EMAIL', 'ivan.bauer90@gmail.com'),
    'website_contact_email' => _env('WEBSITE_CONTACT_EMAIL', 'ivan.bauer90@gmail.com'),

    'providers' => [

    ]
];
