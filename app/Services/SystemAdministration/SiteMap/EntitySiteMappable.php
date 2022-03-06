<?php

namespace App\Services\SystemAdministration\SiteMap;

trait EntitySiteMappable
{
    public function toSiteMapUrl(): SiteMapUrl
    {
        return new SiteMapUrl($this->getUrl(), $this->priority(), $this->changeFreq(), $this->lastmod());
    }

    public function lastmod(): ?string
    {
        return $this->updated_at;
    }

    public function priority(): ?string
    {
        return $this->priority ?? '0.5';
    }

    public function changeFreq(): ChangeFreq
    {
        return ChangeFreq::daily;
    }
}