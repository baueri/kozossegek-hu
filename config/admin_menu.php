<?php

return [
    [
        'title' => 'Áttekintés',
        'icon' => 'home',
        'as' => 'admin.dashboard',
    ],
    [
        'title' => 'Bejegyzések',
        'icon' => 'file-alt',
        'as' => 'admin.page.list',
        'submenu' => [
            [
                'title' => 'Bejegyzések',
                'icon' => 'file-alt',
                'as' => 'admin.page.list',
                'similars' => ['admin.page.edit', 'admin.page.trash', 'admin.page.create'],
            ], ['title' => 'Feltöltések',
                'icon' => 'images',
                'as' => 'admin.content.upload.list',
            ],
        ],
    ],
    [
        'title' => 'Közösségek',
        'icon' => 'comments',
        'as' => 'admin.group.list',
        'similars' => ['admin.group.create', 'admin.group.do_create'],
        'submenu' => [
            [
                'title' => 'Közösségek',
                'icon' => 'comments',
                'as' => 'admin.group.list',
                'similars' => ['admin.group.edit', 'admin.group.trash', 'admin.group.validate', 'admin.group.list.pending'],
            ],
            [
                'title' => 'Létrehozás',
                'icon' => 'plus',
                'as' => 'admin.group.create',
            ],
            [
                'title' => 'Címkék',
                'icon' => 'tags',
                'as' => 'admin.tags.list',
            ],
            [
                'title' => 'Karbantartás',
                'icon' => 'exclamation-triangle',
                'as' => 'admin.group.maintenance',
            ],
        ],
    ],
    [
        'title' => 'Mozgalmak, rendek',
        'icon' => 'landmark',
        'as' => 'admin.spiritual_movement.list',
        'submenu' => [
            [
                'title' => 'Mozgalmak, rendek',
                'icon' => 'landmark',
                'as' => 'admin.spiritual_movement.list',
                'similars' => ['admin.spiritual_movement.edit'],
            ],
            [
                'title' => 'Létrehozás',
                'icon' => 'plus',
                'as' => 'admin.spiritual_movement.create',
                'similars' => ['admin.spiritual_movement.do_create']
            ]
        ]
    ],
    [
        'title' => 'Intézmények',
        'icon' => 'church',
        'as' => 'admin.institute.list',
        'submenu' => [
            [
                'title' => 'Intézmények',
                'icon' => 'church',
                'as' => 'admin.institute.list',
                'similars' => ['admin.institute.edit'],
            ],
            [
                'title' => 'Létrehozás',
                'icon' => 'plus',
                'as' => 'admin.institute.create',
            ],
            [
                'title' => 'Importálás',
                'icon' => 'cloud-upload-alt',
                'as' => 'admin.institute.import',
            ],
        ],
    ],
    [
        'title' => 'Felhasználók',
        'icon' => 'users',
        'as' => 'admin.user.list',
        'submenu' => [
            [
                'title' => 'Felhasználók',
                'icon' => 'users',
                'as' => 'admin.user.list',
                'similars' => ['admin.user.edit', 'admin.user.profile', 'admin.user.managed_groups'],
            ],
            [
                'title' => 'Létrehozás',
                'icon' => 'plus',
                'as' => 'admin.user.create',
            ],
        ],
    ],

    [
        'title' => 'Email sablonok',
        'icon' => 'envelope',
        'as' => 'admin.email_template.list',
    ],
    [
        'title' => 'Statisztika',
        'icon' => 'chart-bar',
        'as' => 'admin.statistics',
        'submenu' => [
            [
                'title' => 'Város alapján',
                'icon' => 'city',
                'as' => 'admin.statistics',
            ],
            [
                'title' => 'Térkép (lefedettség)',
                'icon' => 'map-marked-alt',
                'as' => 'admin.statistics.map',
            ],
            [
                'title' => 'Kulcsszavak városonként',
                'icon' => 'tag',
                'as' => 'admin.statistics.keywords',
            ]
        ]
    ],
    [
        'title' => 'Gépház',
        'icon' => 'cog',
        'as' => 'admin.release_notes',
        'submenu' => [
            [
                'title' => 'Gépház',
                'icon' => 'cog',
                'as' => 'admin.release_notes',
            ],
            [
                'title' => 'Eseménynapló',
                'icon' => 'file-signature',
                'as' => 'admin.event_log',
            ],
            [
                'title' => 'Hibanapló',
                'icon' => 'exclamation-circle ' . (site_has_error_logs() ? 'text-danger' : ''),
                'as' => 'admin.error_log',
            ],
            [
                'title' => 'Háttérfolyamatok',
                'icon' => 'stopwatch',
                'as' => 'admin.scheduled_tasks'
            ]
        ],
    ],
    [
        'title' => 'Kilépés',
        'link_class' => 'text-danger',
        'icon' => 'sign-out-alt',
        'as' => 'logout',
    ],
];
