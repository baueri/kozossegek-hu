<?php

declare(strict_types=1);

namespace App\Portal\Controllers;

use App\Enums\AgeGroup;
use App\Enums\GroupStatus;
use App\Enums\JoinMode;
use App\Enums\OccasionFrequency;
use App\Enums\Tag;
use App\Enums\WeekDay;
use BackedEnum;

class ApiDocsController extends PortalController
{
    public function show(): string
    {
        return $this->mintView->render('pages/api-docs.php', [
            'fieldValues' => [
                [
                    'name' => 'name',
                    'type' => 'string',
                    'required' => 'create',
                    'hint' => 'A közösség neve.',
                    'values' => [],
                ],
                [
                    'name' => 'description',
                    'type' => 'string',
                    'required' => 'create',
                    'hint' => 'A közösség leírása.',
                    'values' => [],
                ],
                [
                    'name' => 'group_leaders',
                    'type' => 'string',
                    'required' => 'create',
                    'hint' => 'A közösségvezető(k) neve.',
                    'values' => [],
                ],
                [
                    'name' => 'institute_id',
                    'type' => 'integer',
                    'required' => 'create_alt',
                    'hint' => 'Meglévő intézmény azonosítója. Létrehozáskor két megoldás közül választhatsz: vagy megadod az institute_id-t, vagy az institute objektumot. A kettőt egyszerre ne küldd.',
                    'values' => [
                        [
                            'value' => 'pl. 285',
                            'label' => 'egy létező intézmény id-ja',
                        ],
                        [
                            'value' => 'GET /api/v1/search-institute',
                            'label' => 'intézménykereső (auth nélkül), pl. ?search=Szent+Jozsef',
                        ],
                    ],
                ],
                [
                    'name' => 'institute',
                    'type' => 'object',
                    'required' => 'create_alt',
                    'hint' => 'Új intézmény adatai, ha nincs a listában. Létrehozáskor két megoldás közül választhatsz: vagy megadod az institute objektumot, vagy az institute_id-t. A kettőt egyszerre ne küldd. Az új intézmény jóváhagyásra vár.',
                    'values' => [
                        ['value' => 'name', 'label' => 'string - intézmény / plébánia neve (kötelező)'],
                        ['value' => 'leader_name', 'label' => 'string - plébános / intézményvezető neve (kötelező)'],
                        ['value' => 'city', 'label' => 'string - település (kötelező)'],
                        ['value' => 'district', 'label' => 'string - városrész (opcionális)'],
                        ['value' => 'address', 'label' => 'string - cím (opcionális)'],
                    ],
                ],
                [
                    'name' => 'age_group',
                    'type' => 'array<string>',
                    'required' => 'create',
                    'hint' => 'Legalább egy érték kötelező létrehozáskor.',
                    'values' => $this->enumOptions(AgeGroup::cases()),
                ],
                [
                    'name' => 'on_days',
                    'type' => 'array<string>',
                    'required' => false,
                    'hint' => 'A hét napjai, amikor a közösség találkozik.',
                    'values' => $this->enumOptions(WeekDay::cases()),
                ],
                [
                    'name' => 'occasion_frequency',
                    'type' => 'string',
                    'required' => 'create',
                    'hint' => 'Kötelező létrehozáskor.',
                    'values' => $this->enumOptions(OccasionFrequency::cases()),
                ],
                [
                    'name' => 'join_mode',
                    'type' => 'string|null',
                    'required' => false,
                    'hint' => 'Csatlakozás módja. Létrehozáskor elhagyható. Frissítéskor: ha nem küldöd, marad a régi érték; null-lal törölheted.',
                    'values' => $this->enumOptions(JoinMode::cases()),
                ],
                [
                    'name' => 'status',
                    'type' => 'string',
                    'required' => 'update',
                    'hint' => 'Csak frissítéskor írható; létrehozáskor a közösség jóváhagyásra vár.',
                    'values' => $this->enumOptions(GroupStatus::cases()),
                ],
                [
                    'name' => 'tags',
                    'type' => 'array<string>',
                    'required' => false,
                    'hint' => 'Az összes választható címke.',
                    'values' => $this->enumOptions(Tag::cases()),
                ],
                [
                    'name' => 'spiritual_movement_id',
                    'type' => 'integer|null',
                    'required' => false,
                    'hint' => 'Lelkiségi mozgalom vagy szerzetesközösség azonosítója. Létrehozáskor elhagyható, vagy null. Frissítéskor: ha nem küldöd, a korábbi mozgalom megmarad; ha leválasztanád, küldj null-t.',
                    'values' => [
                        [
                            'value' => 'pl. 12',
                            'label' => 'egy létező mozgalom id-ja',
                        ],
                        [
                            'value' => 'GET /api/v1/search-spiritual-movement',
                            'label' => 'lista / kereső (auth nélkül), pl. ?search=Regnum',
                        ],
                        [
                            'value' => 'null',
                            'label' => 'nincs mozgalom / leválasztás frissítéskor',
                        ],
                    ],
                ],
                [
                    'name' => 'image',
                    'type' => 'string (base64)',
                    'required' => false,
                    'hint' => 'Base64 kódolt kép (frissítéskor is).',
                    'values' => [],
                ],
            ],
        ]);
    }

    /**
     * @param list<BackedEnum> $cases
     * @return list<array{value: string, label: string}>
     */
    private function enumOptions(array $cases): array
    {
        return array_map(
            static fn (BackedEnum $case): array => [
                'value' => (string) $case->value,
                'label' => method_exists($case, 'translate') ? $case->translate() : (string) $case->value,
            ],
            $cases
        );
    }
}
