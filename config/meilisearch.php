<?php

declare(strict_types=1);

return [
    'enabled' => env('MEILI_ENABLED'),
    'host' => env('MEILI_HOST'),
    'api_key' => env('MEILI_MASTER_KEY'),
    'settings' => [
        'filterableAttributes' => [
            'city',
            'name',
            'institute_name',
            'institute_name2',
            'description',
            'tags',
            'tag_ids',
            'age_group',
            'age_group_text',
            'spiritual_movement'
        ],
        'searchableAttributes' => [
            'city',
            'name',
            'institute_name',
            'institute_name2',
            'description',
            'tags',
            'age_group_text',
            'spiritual_movement'
        ],
        'rankingRules' => [
            'words',
            'typo',
            'sort',
            'proximity',
            'attribute',
            'exactness'
        ]
    ]
];
