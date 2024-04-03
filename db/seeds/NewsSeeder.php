<?php

declare(strict_types=1);

use App\Enums\PageStatus;
use App\Enums\PageType;
use App\QueryBuilders\Users;
use Phinx\Seed\AbstractSeed;

class NewsSeeder extends AbstractSeed
{
    public const int MAX_RANDOM_IMAGES = 1084;

    public function run(): void
    {
        $faker = Faker\Factory::create('hu_HU');
        $faker->unique();

        $images = collect(range(1, self::MAX_RANDOM_IMAGES))
            ->shuffle()
            ->take(100)
            ->map(fn($number) => "https://picsum.photos/id/{$number}/1120/480")
            ->values();

        $pages = [];
        $userId = Users::query()->fetchFirst('id');

        for ($i = 0; $i < 100; $i++) {
            $pages[] = [
                'title' => $faker->sentence(),
                'slug' => $faker->slug(),
                'content' => '<p>' . implode('</p><p>', $faker->paragraphs(rand(3, 5))) . '</p>',
                'status' => PageStatus::PUBLISHED->value(),
                'page_type' => PageType::blog->value(),
                'created_at' => $faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d H:i:s'),
                'header_image' => $images[$i] ?? null,
                'user_id' => $userId,
            ];
        }

        $this->table('pages')->insert($pages)->save();
    }
}
