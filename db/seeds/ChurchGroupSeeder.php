<?php

declare(strict_types=1);

use App\Enums\AgeGroup;
use App\Enums\GroupPending;
use App\Enums\JoinMode;
use App\Enums\OccasionFrequency;
use App\Enums\Tag;
use App\Enums\WeekDay;
use App\Helpers\GroupHelper;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\GroupComments;
use App\QueryBuilders\Institutes;
use App\QueryBuilders\SpiritualMovements;
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
        $tags = Tag::collect();
        $days = WeekDay::collect();
        $admin = Users::query()->admin()->fetchFirst('id');

        for ($i = 0; $i < 50; $i++) {
            db()->transaction(function() use ($faker, $institutes, $spiritualMovements, $ageGroups, $tags, $days, $admin) {

                $certified = rand(0, 1) > 0.1;
                $addComments = rand(0, 1) > 0.95;

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
                        'pending' => GroupPending::confirmed,
                        'join_mode' => JoinMode::random()->value(),
                        'user_id' => $user,
                        'document' => $certified ? 'igazolas.pdf' : null
                    ]);

                    if ($addComments) {
                        GroupComments::query()->insert([
                            'group_id' => $group->getId(),
                            'comment' => $faker->sentence(),
                            'last_comment_user' => $admin
                        ]);
                    }

                    if ($certified) {
                        mkdir(dirname($group->getDocumentPath()), 0777, true);
                        if (!symlink(root()->resources()->path('igazolas.pdf'), $group->getDocumentPath())) {
                            throw new RuntimeException('Failed to create symlink');
                        }
                    }

                    $groupTags = $tags->shuffle()->take(rand(3, 6));
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
                } catch (Throwable $e) {
                    if (str_contains($e->getMessage(), 'Duplicate entry')) {
//                        continue;
                    } else {
                        throw $e;
                    }
                }
//                db()->commit();
            });
        }

        Out::info('Rebuilding search engine...');
        app(RebuildSearchEngine::class)->run();
        app(OpenStreetMapSync::class)->handle();
    }
}
