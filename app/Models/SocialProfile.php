<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SocialProvider;
use Framework\Model\Entity;

/**
 * @property string $user_id
 * @property string $social_provider
 * @property string $social_id
 */
class SocialProfile extends Entity
{
    private ?SocialProvider $provider = null;

    public function getProvider(): SocialProvider
    {
        return $this->provider ??= SocialProvider::from($this->social_provider);
    }

    public function icon(): string
    {
        return $this->getProvider()->icon();
    }

    public function text(): string
    {
        return $this->getProvider()->text();
    }
}