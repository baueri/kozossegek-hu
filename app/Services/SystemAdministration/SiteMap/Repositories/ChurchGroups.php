<?php

namespace App\Services\SystemAdministration\SiteMap\Repositories;

use App\QueryBuilders\GroupViews;
use Framework\Support\Collection;

class ChurchGroups extends Repository
{
    public function getSiteMapUrls(): Collection
    {
        return GroupViews::query()
            ->active()
            ->get()
            ->map
            ->toSiteMapUrl();
    }
}