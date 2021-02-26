<?php

return [
    'file_transfer' => [
        'files_to_deploy' => ['app', 'config', 'framework', 'public', 'resources', 'routes', 'boot.php'],
    ],
    'production' => [
        'branch' => 'master',
        'env_file' => '_env/.env_eles.php',
        'host' => [
            'domain' => 'ftp.nethely.hu',
            'user' => 'kozossegek_hu',
            'password' => 'cheese90ny',
            'cwd' => 'eles'
        ]
    ],
    'development' => [
        'branch' => 'dev',
        'env_file' => '_env/.env_demo.php',
        'host' => [
            'domain' => 'ftp.nethely.hu',
            'port' => 22,
            'user' => 'kozossegek_hu',
            'password' => 'cheese90ny',
            'cwd' => 'demo'
        ]
    ]
];
