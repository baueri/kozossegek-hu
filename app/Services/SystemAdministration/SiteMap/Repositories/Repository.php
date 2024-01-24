<?php

namespace App\Services\SystemAdministration\SiteMap\Repositories;

use App\Services\SystemAdministration\SiteMap\SiteMapUrl;
use Framework\Support\Collection;

abstract class Repository
{
    /**
     * @return Collection<SiteMapUrl>
     */
    abstract public function getSiteMapUrls(): Collection;
}