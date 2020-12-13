 <?php

return [
    [
        'title' => 'Vezérlőpult',
        'icon' => 'home',
        'as' => 'admin.dashboard'
    ],
    [
        'title' => 'Tartalom',
        'icon' => 'file',
        'as' => 'admin.page.list',
        'submenu' => [
            [
                'title' => 'Oldalak',
                'icon' => 'file',
                'as' => 'admin.page.list',
                'similars' => ['admin.page.edit', 'admin.page.trash']
            ], [
                'title' => 'Új oldal',
                'icon' => 'plus',
                'as' => 'admin.page.create',
            ], [
                'title' => 'Feltöltések',
                'icon' => 'images',
                'as' => 'admin.content.upload.list'
            ]
        ]
    ],
    [
        'title' => 'Közösségek',
        'icon' => 'comments',
        'as' => 'admin.group.list',
        'similars' => ['admin.group.create'],
        'submenu' => [
            [
                'title' => 'Közösségek',
                'icon' => 'comments',
                'as' => 'admin.group.list',
                'similars' => ['admin.group.edit']
            ],
            [
                'title' => 'Új közösség',
                'icon' => 'plus',
                'as' => 'admin.group.create',
            ],
            [
                'title' => 'Lelkiségi mozgalmak',
                'icon' => 'landmark',
                'as' => 'admin.spiritual_movements',
            ],
            [
                'title' => 'Címkék',
                'icon' => 'tags',
                'as' => 'admin.tags.list'
            ],
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
                'similars' => ['admin.institute.edit']
            ],
            [
                'title' => 'Új intézmény',
                'icon' => 'plus',
                'as' => 'admin.institute.create',
            ],
            [
                'title' => 'Importálás',
                'icon' => 'cloud-upload-alt',
                'as' => 'admin.institute.import'
            ]
        ]
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
                'similars' => ['admin.user.edit', 'admin.user.profile'],
            ],
            [
                'title' => 'Új felhasználó',
                'icon' => 'plus',
                'as' => 'admin.user.create'
            ]
        ]
    ],

    [
        'title' => 'Email sablon',
        'icon' => 'envelope',
        'as' => 'admin.email_template.list',
    ],
    [
        'title' => 'Widgetek',
        'icon' => 'layer-group',
        'as' => 'admin.widget.list',
        'similars' => ['admin.widget.create', 'admin.widget.edit'],
        'submenu' => [
            [
                'title' => 'Widgetek',
                'icon' => 'layer-group',
                'as' => 'admin.widget.list',
            ]
        ]
    ],
    [
        'title' => 'Gépház',
        'icon' => 'cog',
        'as' => 'admin.settings',
        'submenu' => [
            [
                'title' => 'Gépház',
                'icon' => 'cog',
                'as' => 'admin.settings',
            ],
            [
                'title' => 'Eseménynapló',
                'icon' => 'file-signature',
                'as' => 'admin.event_log',
            ],
            [
                'title' => 'Hibanapló',
                'icon' => 'exclamation-circle ' . (file_exists(ROOT . 'error.log') && filesize(ROOT . 'error.log') ? 'text-danger' : ''),
                'as' => 'admin.error_log',
            ],
            [
                'title' => 'Verzióinformáció',
                'icon' => 'info-circle',
                'as' => 'admin.release_notes'
            ]
        ]
    ],
    [
        'title' => 'Kilépés',
        'link_class' => 'text-danger',
        'icon' => 'sign-out-alt',
        'as' => 'logout'
    ],
];
