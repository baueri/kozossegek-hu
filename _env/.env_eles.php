<?php

return [
    'STORAGE_PATH' => ROOT . '../storage/eles' . DS,
    'SITE_URL' => 'https://kozossegek.hu',
    'SITE_NAME' => 'kozossegek.hu',
    'ENVIRONMENT' => 'production',
    'DEBUG' => false,

    'DB_HOST' => 'localhost',
    'DB_USER' => 'kozossegek_eles',
    'DB_PASSWORD' => 'tlx0BGzO',
    'DB_NAME' => 'kozossegek_eles',

    'DB_PORT' => '3306',
    'BASE_AUTH' => false,
    'COMING_SOON' => false,

    //EMAIL beállítások
    'EMAIL_HOST' => 'mail.nethely.hu',
    'EMAIL_PORT' => '1025',
    'EMAIL_ADDRESS' => 'noreply@kozossegek.hu',
    'EMAIL_PASSWORD' => '***REMOVED***',
    'EMAIL_SSL' => 'STARTTLS',

    'CONTACT_EMAIL' => 'kozossegek.szeged@gmail.com',
    'ERROR_EMAIL' => 'ivan.bauer90@gmail.com',
    'LEGAL_NOTICE_VERSION' => 0,
    'LEGAL_NOTICE_DATE' => '2021-07-07',

    'GROUP_SEND_NOTIFICATION_AFTER' => '6 MONTH',
    'GROUP_INACTIVATE_AFTER_NOTIFICATION' => '1 MONTH',
    'APP_TIMEZONE' => 'Europe/Budapest'
];
