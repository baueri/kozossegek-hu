<?php

declare(strict_types=1);

namespace App\Services\SystemAdministration\SiteMap;

enum ChangeFreq
{
    case always;
    case hourly;
    case daily;
    case weekly;
    case monthly;
    case yearly;
    case never;
}
