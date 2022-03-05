<?php

namespace App\Services\SystemAdministration\SiteMap;

use App\Services\SystemAdministration\SiteMap\Repositories\ChurchGroups;
use App\Services\SystemAdministration\SiteMap\Repositories\SpiritualMovementRepository;
use App\Services\SystemAdministration\SiteMap\Repositories\Repository;
use App\Services\SystemAdministration\SiteMap\Repositories\StaticPages;
use Framework\File\File;
use Framework\File\FileManager;
use Framework\Support\Collection;
use SimpleXMLElement;

class SiteMapGenerator
{
    /**
     * @var Repository[]
     */
    private readonly array $repositories;

    public function __construct()
    {
        $this->repositories = [
            StaticPages::class,
            SpiritualMovementRepository::class,
            ChurchGroups::class
        ];
    }

    public function run(): void
    {
        $urls = collect();

        foreach ($this->repositories as $repository) {
            $urls = $urls->merge(
                resolve($repository)->getSiteMapUrls()
            );
        }

        file_put_contents(ROOT . 'public/sitemap.xml', $this->generateSiteMap($urls));
    }

    /**
     * @param \App\Services\SystemAdministration\SiteMap\SiteMapUrl[]|Collection $siteMapUrls
     */
    private function generateSiteMap(array|Collection $siteMapUrls): string
    {
        $xml = new SimpleXMLElement("<?xml version='1.0' encoding='utf-8'?><urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9' xmlns:xhtml='http://schema.com/xhtml'/>");

        foreach ($siteMapUrls as $siteMapUrl) {
            $urlData = $siteMapUrl->toXMLArray();
            $url = $xml->addChild('url');
            array_walk_recursive($urlData, [$url, 'addChild']);
        }

        return $xml->asXML();
    }
}