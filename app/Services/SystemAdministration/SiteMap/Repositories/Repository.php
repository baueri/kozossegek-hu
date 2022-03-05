<?php

namespace App\Services\SystemAdministration\SiteMap\Repositories;

use Framework\Support\Collection;

abstract class Repository
{
    /**
     * @return \Framework\Support\Collection<\App\Services\SystemAdministration\SiteMap\SiteMapUrl>
     */
    abstract public function getSiteMapUrls(): Collection;
}