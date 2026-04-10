<?php

declare(strict_types=1);

namespace App\Services\AIFaker;

use Baueri\AIFaker\Cache\FileCacheManager;
use Baueri\AIFaker\Contracts\AIProviderInterface;
use Baueri\AIFaker\Generator\Fake;
use Baueri\AIFaker\Providers\GoogleAIStudioProvider;
use Baueri\AIFaker\Providers\OpenAIProvider;

class FakerFactory
{
    public static function fromConfig(
        string $provider,
        array $providers,
        bool $cacheEnabled = false,
        ?string $cacheDir = null,
    ): Fake {
        $provider = trim($provider);
        $config = $providers[$provider] ?? [];

        return match ($provider) {
            'google_ai_studio' => self::googleAIStudio(
                (string) ($config['api_key'] ?? ''),
                (string) ($config['model'] ?? 'gemini-flash-latest'),
                (int) ($config['timeout'] ?? 120),
                $cacheEnabled,
                $cacheDir
            ),
            default => self::openAI(
                (string) ($config['api_key'] ?? ''),
                (string) ($config['model'] ?? 'gpt-4.1-mini'),
                (int) ($config['timeout'] ?? 120),
                $cacheEnabled,
                $cacheDir
            ),
        };
    }

    public static function openAI(
        string $apiKey,
        string $model = 'gpt-4.1-mini',
        int $timeout = 120,
        bool $cacheEnabled = false,
        ?string $cacheDir = null
    ): Fake
    {
        $provider = new OpenAIProvider(
            apiKey: $apiKey,
            model: $model,
            timeout: $timeout
        );

        return self::buildFake($provider, $cacheEnabled, $cacheDir);
    }

    public static function googleAIStudio(
        string $apiKey,
        string $model = 'gemini-flash-latest',
        int $timeout = 120,
        bool $cacheEnabled = false,
        ?string $cacheDir = null
    ): Fake
    {
        $provider = new GoogleAIStudioProvider(
            apiKey: $apiKey,
            model: $model,
            timeout: $timeout
        );

        return self::buildFake($provider, $cacheEnabled, $cacheDir);
    }

    private static function buildFake(AIProviderInterface $provider, bool $cacheEnabled, ?string $cacheDir): Fake
    {
        if ($cacheEnabled) {
            return new Fake($provider, new FileCacheManager($cacheDir));
        }

        return new Fake($provider);
    }
}
