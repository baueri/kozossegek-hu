<?php

namespace App\Enums;

use Framework\Traits\EnumTrait;

enum OsmType: string
{
    use EnumTrait;

    case city = 'city';
    case institute = 'institute';
    case city_stat = 'city_stat';
}
