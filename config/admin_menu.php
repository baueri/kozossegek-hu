<?php

return [
    [
        'title' => 'Vezérlőpult',
        'icon' => 'home',
        'as' => 'admin.dashboard'
    ],
    [
        'title' => 'Oldalak',
        'icon' => 'file',
        'as' => 'admin.page.list',
        'submenu' => [
            [
                'title' => 'Oldalak',
                'icon' => 'stream',
                'as' => 'admin.page.list',
                'similars' => ['admin.page.edit', 'admin.page.trash']
            ], [
                'title' => 'Új oldal',
                'icon' => 'plus',
                'as' => 'admin.page.create',
            ],
        ]
    ],
    [
        'title' => 'Közösségek',
        'icon' => 'comments',
        'as' => 'admin.group.list',
        'submenu' => [
            [
                'title' => 'Közösségek',
                'icon' => 'stream',
                'as' => 'admin.group.list',
                'similars' => ['admin.group.edit']
            ], [
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
                'as' => 'admin.tags'
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
        ]
    ],
    [
        'title' => 'Felhasználók',
        'icon' => 'users',
        'as' => 'admin.user.list'
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
        'as' => 'admin.logout'
    ],
];
