<?php

namespace App\Services\SystemAdministration\SiteMap\Repositories;

use App\QueryBuilders\Pages;
use App\Services\SystemAdministration\SiteMap\ChangeFreq;
use App\Services\SystemAdministration\SiteMap\SiteMapUrl;
use Framework\Support\Collection;

class StaticPages extends Repository
{
    public function getSiteMapUrls(): Collection
    {
        return Pages::query()
            ->published()
            ->notDeleted()
            ->orderBy('priority', 'desc')
            ->select('slug, updated_at, priority')
            ->get()
            ->map
            ->toSiteMapUrl()
            ->unshift(new SiteMapUrl(loc: route('home'), priority: '1.0', changefreq: ChangeFreq::yearly, lastmod: '1.0'))
            ->unshift(new SiteMapUrl(loc: route('portal.register_group'), priority: '0.8', changefreq: ChangeFreq::yearly, lastmod: '0.8'))
            ->unshift(new SiteMapUrl(loc: route('login'), priority: '0.6', changefreq: ChangeFreq::yearly, lastmod: '0.6'))
            ->unshift(new SiteMapUrl(loc: route('portal.spiritual_movements'), priority: '0.7', changefreq: ChangeFreq::yearly, lastmod: '0.7'));
    }
}
