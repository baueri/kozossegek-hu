<?php

declare(strict_types=1);

namespace App\Services\SystemAdministration\SiteMap;

readonly class SiteMapUrl
{
    public function __construct(
        public string      $loc,
        public ?string     $priority = '0.5',
        public ?ChangeFreq $changefreq = null,
        public ?string     $lastmod = null
    ) {
    }

    public function toXMLArray(): array
    {
        return array_flip(
            array_filter([
                'loc' => htmlspecialchars($this->loc),
                'lastmod' => $this->lastmod ? date('c', strtotime($this->lastmod)) : null,
                'changefreq' => $this->changefreq?->name,
                'priority' => $this->priority
            ])
        );
    }
}
