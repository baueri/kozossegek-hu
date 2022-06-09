<?php

namespace App\Services\SystemAdministration\SiteMap\Repositories;

use App\Models\CityStat;
use App\Repositories\CityStatistics;
use App\Services\SystemAdministration\SiteMap\ChangeFreq;
use App\Services\SystemAdministration\SiteMap\SiteMapUrl;
use Framework\Support\Collection;

class PopularCities extends Repository
{
    public function getSiteMapUrls(): Collection
    {
        return collect(
            CityStatistics::query()
                ->select('city')
                ->orderBy('sum(search_count)', 'desc')
                ->groupBy('city')
                ->limit(15)
                ->get()
        )->map(fn (CityStat $row) => $this->createSiteMapUrl($row));
    }

    private function createSiteMapUrl(CityStat $row): SiteMapUrl
    {
        return new SiteMapUrl(
            route('portal.groups', ['varos' => $row->city]),
            '0.7',
            ChangeFreq::monthly
        );
    }
}
