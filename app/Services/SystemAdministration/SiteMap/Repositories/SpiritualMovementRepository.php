<?php

namespace App\Services\SystemAdministration\SiteMap\Repositories;

use App\QueryBuilders\SpiritualMovements;
use Framework\Support\Collection;

class SpiritualMovementRepository extends Repository
{
    public function getSiteMapUrls(): Collection
    {
        return SpiritualMovements::query()
            ->hightLighted()
            ->orderBy('name', 'desc')
            ->get()
            ->map
            ->toSiteMapUrl();
    }
}