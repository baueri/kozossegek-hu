<?php

declare(strict_types=1);

namespace App\Enums;

enum SocialProvider: string
{
    use EnumTrait;

    case facebook = 'facebook';

    case google = 'google';

    public function icon(): string
    {
        return match ($this) {
            self::facebook => 'fab fa-facebook text-primary',
            self::google => 'fab fa-google text-danger'
        };
    }

    public function text(): string
    {
        return match ($this) {
            self::facebook => 'Facebook',
            self::google => 'Google'
        };
    }
}