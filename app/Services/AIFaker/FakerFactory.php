<?php

declare(strict_types=1);

namespace App\Services\AIFaker;

use Baueri\AIFaker\Cache\FileCacheManager;
use Baueri\AIFaker\Generator\Fake;
use Baueri\AIFaker\Providers\OpenAIProvider;

class FakerFactory
{
    public static function openAI(string $apiKey, ?string $cacheDir = null): Fake
    {
        $provider = new OpenAIProvider($apiKey, timeout: 120);

        if (config('app.ai_faker_cache_enabled')) {
            $cache = new FileCacheManager($cacheDir);

            return new Fake($provider, $cache);
        }

        return new Fake($provider);
    }
}
