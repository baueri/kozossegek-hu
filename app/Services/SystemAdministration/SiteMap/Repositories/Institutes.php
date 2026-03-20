<?php

declare(strict_types=1);

namespace App\Services\SystemAdministration\SiteMap\Repositories;

use App\Models\Institute;
use App\Services\SystemAdministration\SiteMap\ChangeFreq;
use App\Services\SystemAdministration\SiteMap\SiteMapUrl;
use Framework\Support\Collection;

class Institutes extends Repository
{
    public function getSiteMapUrls(): Collection
    {
        return \App\QueryBuilders\Institutes::query()
            ->whereHas('churchGroups')
            ->map(fn (Institute $institute) => new SiteMapUrl(loc: $institute->groupsUrl(), changefreq: ChangeFreq::monthly, lastmod: $institute->updated_at));
    }
}