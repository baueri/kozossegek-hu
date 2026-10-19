<?php

declare(strict_types=1);

namespace App\Services\SystemAdministration\SiteMap\Repositories;

use App\Models\Event;
use App\QueryBuilders\Events;
use App\Services\SystemAdministration\SiteMap\ChangeFreq;
use App\Services\SystemAdministration\SiteMap\SiteMapUrl;
use Framework\Support\Collection;

class PublishedEvents extends Repository
{
    public function getSiteMapUrls(): Collection
    {
        $urls = collect([
            new SiteMapUrl(
                loc: route('event.list'),
                priority: '0.8',
                changefreq: ChangeFreq::weekly,
                lastmod: null,
            ),
        ]);

        return $urls->merge(
            Events::query()
                ->approved()
                ->whereNotNull('slug')
                ->where('slug', '!=', '')
                ->upcoming()
                ->orderBy('starts_at')
                ->get()
                ->map(fn (Event $event) => $event->toSiteMapUrl())
        );
    }
}
