<?php

use App\Middleware\AdminMiddleware;
use App\Middleware\LoggedInMiddleware;
use Framework\Middleware\JsonApi;
use Framework\Middleware\VerifyCsrfToken;

return [
    'site_url' => _env('SITE_URL'),
    'site_name' => _env('SITE_NAME', 'kozossegek.hu'),
    'environment' => _env('ENVIRONMENT', 'development'),
    'base_auth' => _env('BASE_AUTH', false),
    'base_auth.user' => 'kozossegek',
    'base_auth.password' => '***REMOVED***',
    'docache' => _env('ENVIRONMENT') != 'production',
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

    'legal_notice_version' => _env('LEGAL_NOTICE_VERSION', 0),
    'legal_notice_date' => _env('LEGAL_NOTICE_DATE', '2021-07-07'),

    'providers' => [

    ],

    'named_middleware' => [
        'csrf' => VerifyCsrfToken::class,
        'json' => JsonApi::class,
        'admin' => AdminMiddleware::class,
        'auth' => LoggedInMiddleware::class
    ]
];
