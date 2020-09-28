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
                'similars' => ['admin.page.edit']
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
        ]
    ],
    [
        'title' => 'Intézmények',
        'icon' => 'church',
        'as' => 'admin.institute.list'
    ],
    [
        'title' => 'Felhasználók',
        'icon' => 'users',
        'as' => 'admin.users'
    ],
    [
        'title' => 'Címkék',
        'icon' => 'tags',
        'as' => 'admin.settings'
    ],
    [
        'title' => 'Gépház',
        'icon' => 'cog',
        'as' => 'admin.settings'
    ],
    [
        'title' => 'Kilépés',
        'link_class' => 'text-danger',
        'icon' => 'sign-out-alt',
        'as' => 'admin.logout'
    ],
];
