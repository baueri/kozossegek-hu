<?php

declare(strict_types=1);

namespace App\Enums;

enum SocialProvider
{
    use EnumTrait;

    case facebook;

    case google;
}