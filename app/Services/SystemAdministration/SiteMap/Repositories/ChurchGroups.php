<?php

namespace App\Services\SystemAdministration\SiteMap\Repositories;

use App\QueryBuilders\ChurchGroupViews;
use Framework\Support\Collection;

class ChurchGroups extends Repository
{
    public function getSiteMapUrls(): Collection
    {
        return ChurchGroupViews::query()
            ->active()
            ->get()
            ->map
            ->toSiteMapUrl();
    }
}