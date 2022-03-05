<?php

namespace App\Services\SystemAdministration\SiteMap;

trait EntitySiteMappable
{
    public function toSiteMapUrl(): SiteMapUrl
    {
        return new SiteMapUrl($this->getUrl(), $this->lastmod(), $this->priority(), $this->changeFreq());
    }

    public function lastmod(): ?string
    {
        return $this->updated_at;
    }

    public function priority(): ?string
    {
        return $this->priority;
    }

    public function changeFreq(): ChangeFreq
    {
        return ChangeFreq::daily;
    }
}