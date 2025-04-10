<?php

declare(strict_types=1);

namespace App\Services\SystemAdministration\SiteMap\Repositories;

use App\Enums\PageType;
use App\Models\Page;
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
            ->whereIn('page_type', [PageType::blog, PageType::page])
            ->orderBy('priority', 'desc')
            ->select('slug, updated_at, priority, page_type')
            ->get()
            ->map(fn (Page $page) => $page->toSiteMapUrl())
            ->unshift(new SiteMapUrl(loc: route('home'), priority: '1.0', changefreq: ChangeFreq::yearly, lastmod: '1.0'))
            ->unshift(new SiteMapUrl(loc: route('portal.register_group'), priority: '0.8', changefreq: ChangeFreq::yearly, lastmod: '0.8'))
            ->unshift(new SiteMapUrl(loc: route('login'), priority: '0.6', changefreq: ChangeFreq::yearly, lastmod: '0.6'))
            ->unshift(new SiteMapUrl(loc: route('portal.spiritual_movements'), priority: '0.7', changefreq: ChangeFreq::yearly, lastmod: '0.7'))
            ->unshift(new SiteMapUrl(loc: route('portal.blog'), priority: '0.6', changefreq: ChangeFreq::weekly, lastmod: '0.9'));
    }
}
