<?php

declare(strict_types=1);

use App\Enums\AgeGroup;
use App\Enums\JoinMode;
use App\Enums\OccasionFrequency;
use App\Enums\WeekDay;
use App\Helpers\GroupHelper;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\Institutes;
use App\QueryBuilders\SpiritualMovements;
use App\QueryBuilders\Tags;
use App\QueryBuilders\Users;
use App\Services\RebuildSearchEngine;
use App\Services\SystemAdministration\OpenStreetMap\OpenStreetMapSync;
use App\Storage\Base64Image;
use Faker\Factory;
use Framework\Console\Out;
use Framework\Support\Password;
use Legacy\UserRole;
use Phinx\Seed\AbstractSeed;

class ChurchGroupSeeder extends AbstractSeed
{
    public function run(): void
    {
        $faker = Factory::create('hu_HU');
        $faker->unique();

        $institutes = Institutes::query()->orderBy('RAND()')->limit(50)->pluck('id');
        $spiritualMovements = SpiritualMovements::query()->pluck('id');
        $ageGroups = AgeGroup::collect();
        $tags = Tags::query()->collect();
        $days = WeekDay::collect();
        $imageSource = fn () => base64_encode(file_get_contents('https://loremflickr.com/300/300/church,catholic,christian/all'));

        for ($i = 0; $i < 200; $i++) {
            db()->beginTransaction();
            try {
                $user = Users::query()->insert([
                    'name' => $name = $faker->lastName() . ' ' . $faker->firstName(),
                    'email' => $faker->email(),
                    'password' => Password::hash('pw'),
                    'user_group' => UserRole::GROUP_LEADER,
                    'activated_at' => now()
                ]);
                $group = ChurchGroups::query()->create([
                    'name' => mb_ucfirst($faker->words(rand(3, 5), true)),
                    'description' => '<p>' . implode('</p><p>', $faker->paragraphs(rand(3, 5))) . '</p>',
                    'institute_id' => $institutes->random(),
                    'group_leaders' => $name,
                    'group_leader_phone' => rand(0, 1) ? $faker->phoneNumber() : '',
                    'age_group' => $ageGroups->shuffle()->take(rand(1, 3))->implode(','),
                    'on_days' => $days->shuffle()->take(rand(1, 3))->implode(','),
                    'occasion_frequency' => OccasionFrequency::random()->value(),
                    'status' => 'active',
                    'spiritual_movement_id' => rand(1, 7) === 1 ? $spiritualMovements->random() : null,
                    'pending' => 0,
                    'join_mode' => JoinMode::random()->value(),
                    'user_id' => $user
                ]);
                $groupTags = $tags->shuffle()->take(rand(3, 6));
                foreach ($groupTags as $tag) {
                    builder('group_tags')->insert([
                        'group_id' => $group->getId(),
                        'tag' => $tag['slug']
                    ]);
                }
                ChurchGroups::query()->save($group, ['image_url' => GroupHelper::getPublicImagePath((int)$group->getId())]);
                $image = new Base64Image($imageSource());
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
        app(RebuildSearchEngine::class)->run();
        app(OpenStreetMapSync::class)->handle();
    }
}