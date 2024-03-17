<?php

use App\Middleware\AdminMiddleware;
use App\Middleware\LoggedInMiddleware;
use App\Portal\Services\Search\DatabaseSearchGroupRepository;
use App\Portal\Services\Search\MeiliSearchRepository;
use App\Middleware\RefererMiddleware;
use Framework\Middleware\JsonApi;
use Framework\Middleware\Translation;
use Framework\Middleware\VerifyCsrfToken;

return [
    'site_url' => env('SITE_URL'),
    'site_name' => env('SITE_NAME'),
    'environment' => env('ENVIRONMENT'),
    'base_auth' => env('BASE_AUTH', false),
    'base_auth.user' => env('BASE_AUTH_USER'),
    'base_auth.password' => env('BASE_AUTH_PASSWORD'),
    'docache' => env('ENVIRONMENT') != 'production',
    'debug' => env('DEBUG', false),
    'coming_soon' => env('COMING_SOON', false),
    'storage_path' => env('STORAGE_PATH'),

    'email' => env('EMAIL_ADDRESS'),
    'email_password' => env('EMAIL_PASSWORD'),
    'email_host' => env('EMAIL_HOST'),
    'email_port' => env('EMAIL_PORT'),
    'email_ssl' => env('EMAIL_SSL'),

    'contact_email' => env('CONTACT_EMAIL'),
    'error_email' => env('ERROR_EMAIL'),
    'website_contact_email' => env('WEBSITE_CONTACT_EMAIL'),

    'legal_notice_version' => env('LEGAL_NOTICE_VERSION', 0),
    'legal_notice_date' => env('LEGAL_NOTICE_DATE', '2021-07-07'),
    'named_middleware' => [
        'translation' => Translation::class,
        'csrf' => VerifyCsrfToken::class,
        'json' => JsonApi::class,
        'admin' => AdminMiddleware::class,
        'auth' => LoggedInMiddleware::class,
        'referer' => RefererMiddleware::class
    ],
    'exclude_csrf' => [
        'admin.upload_file'
    ],
    'selected_search_driver' => env('SEARCH_DRIVER', 'database'),
    'search_drivers' => [
        'database' => DatabaseSearchGroupRepository::class,
        'meilisearch' => MeiliSearchRepository::class
    ]
];
