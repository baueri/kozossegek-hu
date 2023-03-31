<?php

return [
    'STORAGE_PATH' => ROOT . '../storage/demo' . DS,
    'SITE_URL' => 'https://demo.kozossegek.hu',
    'SITE_NAME' => 'demo.kozossegek.hu',
    'ENVIRONMENT' => 'development',
    'DEBUG' => false,
    'DB_HOST' => 'mysql.nethely.hu',
    'DB_USER' => 'kozossegek_demo',
    'DB_PASSWORD' => '7DkSfbNb',
    'DB_NAME' => 'kozossegek_demo',
    'DB_PORT' => '3306',
    'BASE_AUTH' => false,
    'COMING_SOON' => false,

    //EMAIL beállítások
    'EMAIL_HOST' => 'mail.nethely.hu',
    'EMAIL_PORT' => '1025',
    'EMAIL_ADDRESS' => 'noreply@kozossegek.hu',
    'EMAIL_PASSWORD' => '***REMOVED***',
    'EMAIL_SSL' => 'STARTTLS',

    'CONTACT_EMAIL' => 'ivan.bauer90@gmail.com',
    'ERROR_EMAIL' => 'error@kozossegek.hu',

    'LEGAL_NOTICE_DATE' => '2021-07-07',

    'GROUP_SEND_NOTIFICATION_AFTER' => '6 MONTH',
    'GROUP_INACTIVATE_AFTER_NOTIFICATION' => '1 MONTH',
    'APP_TIMEZONE' => 'Europe/Budapest'
];
