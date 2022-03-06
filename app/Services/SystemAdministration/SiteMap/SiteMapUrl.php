<?php

namespace App\Services\SystemAdministration\SiteMap;

use DOMElement;
use SimpleXMLElement;

class SiteMapUrl
{
    public function __construct(
        public readonly string $loc,
        public readonly ?string $priority = '0.5',
        public readonly ?ChangeFreq $changefreq = null,
        public readonly ?string $lastmod = null
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
