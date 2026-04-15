<?php

declare(strict_types=1);

use App\Enums\AgeGroup;
use App\Enums\JoinMode;
use App\Enums\OccasionFrequency;
use App\Enums\Tag;
use App\Enums\UserRole;
use App\Enums\WeekDay;
use App\Helpers\GroupHelper;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\Institutes;
use App\QueryBuilders\SpiritualMovements;
use App\QueryBuilders\Users;
use App\Services\RebuildSearchEngine;
use App\Services\SystemAdministration\OpenStreetMap\OpenStreetMapSync;
use App\Storage\Base64Image;
use Baueri\AIFaker\Generator\Fake;
use Faker\Factory;
use Framework\Console\Out;
use Framework\Support\Password;
use Phinx\Seed\AbstractSeed;

class ChurchGroupSeeder extends AbstractSeed
{
    public function run(): void
    {
        $aiFaker = app()->get(Fake::class)
            ->for('fictive catholic church group')
            ->language('hu')
            ->fields(['name', 'description'])
            ->count(50)
            ->batch(10)
            ->context([
                'name' => ['A small catholic church group name, e.g pesti ferencesek, Tiszavirág közösség, Szegletkő, Regnum Marianum, Adoremus imakör, etc.'],
                'description' => [
                    'must be maximum 3-5 paragraphs, each paragraph contains 5-8 sentences',
                    'it is an introduction of a church group',
                    'can be either in plural first person or singular third person',
                    'each paragraph wrapped in <p> tag',
                    'some groups can use emojis, italic and bold text, or even unordered lists, headings etc'
                ]
            ])
            ->cursor();

        $faker = Factory::create('hu_HU');
        $faker->unique();

        $institutes = Institutes::query()->orderBy('RAND()')->limit(50)->pluck('id');
        $spiritualMovements = SpiritualMovements::query()->pluck('id');
        $ageGroups = AgeGroup::collect();
        $tags = Tag::collect();
        $days = WeekDay::collect();
        foreach ($aiFaker as $aiItem) {

            db()->beginTransaction();
            $ag = $ageGroups->shuffle()->take(rand(1, 3))->implode(',');
            $groupTags = $tags->shuffle()->take(rand(3, 6));

            $aiItem = $aiFaker->fetch([
                'target age group(s)' => $ag,
                'tags' => $groupTags->implode(',')
            ]);
            Out::info('ai data fetched');

            try {
                $user = Users::query()->insert([
                    'name' => $name = $faker->lastName() . ' ' . $faker->firstName(),
                    'email' => $faker->email(),
                    'password' => Password::hash('pw'),
                    'user_role' => UserRole::GROUP_LEADER,
                    'activated_at' => now()
                ]);
                $group = ChurchGroups::query()->create([
                    'name' => $aiItem['name'],
                    'description' => $aiItem['description'],
                    'institute_id' => $institutes->random(),
                    'group_leaders' => $name,
                    'group_leader_phone' => rand(0, 1) ? $faker->phoneNumber() : '',
                    'age_group' => $ag,
                    'on_days' => $days->shuffle()->take(rand(1, 3))->implode(','),
                    'occasion_frequency' => OccasionFrequency::collect()->random()->value(),
                    'status' => 'active',
                    'spiritual_movement_id' => rand(1, 7) === 1 ? $spiritualMovements->random() : null,
                    'pending' => 0,
                    'join_mode' => JoinMode::collect()->random()->value(),
                    'user_id' => $user
                ]);

                foreach ($groupTags as $tag) {
                    builder('group_tags')->insert([
                        'group_id' => $group->getId(),
                        'tag' => $tag->name
                    ]);
                }
                ChurchGroups::query()->save($group, ['image_url' => GroupHelper::getPublicImagePath((int)$group->getId())]);
                $imageSource = base64_encode(file_get_contents('https://picsum.photos/300/300'));
                $image = new Base64Image($imageSource);
                $image->saveImage($group->getStorageImageDir() . $group->id . '_1.jpg');
            } catch (\Throwable $e) {
                if (str_contains($e->getMessage(), 'Duplicate entry')) {
                    continue;
                } else {
                    throw $e;
                }
            }
            db()->commit();
        }

        Out::info('Rebuilding search engine...');
        app()->get(RebuildSearchEngine::class)->run();
        app()->get(OpenStreetMapSync::class)->handle();
    }
}