<?php

declare(strict_types=1);

use App\Portal\Services\UserEventFormHandler;
use App\QueryBuilders\Events;
use App\QueryBuilders\Users;
use Baueri\AIFaker\Generator\Fake;
use Framework\Support\Arr;
use Phinx\Seed\AbstractSeed;

class EventSeeder extends AbstractSeed
{
    public function run()
    {
        $faker = app()->get(Fake::class);
        $handler = app()->get(UserEventFormHandler::class);

        $cursor = $faker->for('event')
            ->fields([
                'name',
                'slug',
                'description',
                'starts_at',
                'ends_at',
                'all_day',
                'lifecycle',
                'organizer',
                'location_name',
                'address',
                'lat', 
                'lng',
                'tags'
            ])
            ->language('hu')
            ->context([
                'the subject of the event can be anything, including  camps, masses, prayers, concerts, pilgrimage, retreat etc',
                'name' => 'random fictive catholic event',
                'lifecycle' => 'active or cancelled',
                'organizer' => 'random fictive catholic community group',
                'all_day' => 'randomly 1 or 0',
                'description' => [
                    'a long description of the catholic event in multiple paragraphs (3-5) and sentences',
                    'May use html: unordered lists, paragraphs, basic font styling.',
                    'may contain randomly: target audience, scheduled programs (with or without starting time), link to facebook event page, emojis, price'
                ],
                'starts_at' => ['format: datetime (Y-m-d H:i:s)', 'after ' . now()->format('Y-m-d'), 'some events may start in next year'],
                'ends_at' => ['format: datetime (Y-m-d H:i:s)', 'end date should be close to start date'],
            ])
            ->count(4)
            ->cursor();

        $user = Users::query()->first()->id;

        foreach ($cursor as $aievent) {
            $raw = base64_encode(file_get_contents('https://picsum.photos/1024/768'));
            $dataUrl = 'data:image/jpeg;base64,' . $raw;
            $paths = $handler->persistFeaturedImage($dataUrl);

            Events::query()
                ->create(array_merge([
                    'user_id' => $user,
                    'status' => 'approved',
                    'featured_image' => $paths['thumb'],
                    'featured_image_poster' => $paths['poster'],
                ], Arr::except($aievent, 'tags')));
        }
    }
}
