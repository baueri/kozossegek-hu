<?php

namespace App\Enums;

enum OsmType: string
{
    use EnumTrait;

    case city = 'city';
    case institute = 'institute';
    case city_stat = 'city_stat';
}
