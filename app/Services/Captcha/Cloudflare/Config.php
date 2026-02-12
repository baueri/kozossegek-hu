<?php

declare(strict_types=1);

namespace App\Services\Captcha\Cloudflare;

readonly class Config
{
    public function __construct(
        public bool $enabled,
        public string $siteKey,
        public string $secret
    ) {
    }
}