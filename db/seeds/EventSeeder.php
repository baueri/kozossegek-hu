<?php

declare(strict_types=1);

use App\Helpers\PathHelper;
use App\QueryBuilders\Events;
use App\QueryBuilders\Users;
use App\Storage\Base64Image;
use Baueri\AIFaker\Generator\Fake;
use Framework\Support\Arr;
use Phinx\Seed\AbstractSeed;

class EventSeeder extends AbstractSeed
{
    public function run()
    {
        $faker = app()->get(Fake::class);

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
                'starts_at' => ['format: datetime (Y-m-d H:i:s)', 'after ' . now()->format('Y-m-d')],
                'ends_at' => ['format: datetime (Y-m-d H:i:s)'],
            ])
            ->count(10)
            ->cursor();

        $user = Users::query()->first()->id;

        foreach ($cursor as $aievent)
        {
            $imageSource = base64_encode(file_get_contents('https://picsum.photos/1024/768'));
            $image = new Base64Image($imageSource);
            $path = $this->getStoragePath($imageSource);
            $image->saveImage($path);

            $event = Events::query()
                ->create(array_merge([
                    'user_id' => $user,
                    'status' => 'approved',
                    'featured_image' => $path
                ], Arr::except($aievent, 'tags')));

            
        }
    }

    private function getStoragePath(string $imageSource)
    {
        $hash = substr(hash('SHA256', $imageSource), 0, 16);
        return env('STORAGE_PATH') . 'public' . DS . 'event' . DS . substr($hash, 0, 2) . DS . substr($hash, 2, 2) . DS . $hash . '.jpg';
    }
}
