<?php

declare(strict_types=1);

namespace App\Services\SystemAdministration\SiteMap\Repositories;

use App\Models\ChurchGroupView;
use App\QueryBuilders\ChurchGroupViews;
use Framework\Support\Collection;

class ChurchGroups extends Repository
{
    public function getSiteMapUrls(): Collection
    {
        return ChurchGroupViews::query()
            ->active()
            ->get()
            ->map(fn (ChurchGroupView $churchGroup) => $churchGroup->toSiteMapUrl());
    }
}
