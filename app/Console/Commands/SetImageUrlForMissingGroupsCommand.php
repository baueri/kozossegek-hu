<?php

namespace App\Console\Commands;

use App\Services\SystemAdministration\SetImageUrlForMissingGroups;
use Framework\Console\Command;

class SetImageUrlForMissingGroupsCommand implements Command
{
    /**
     * @var SetImageUrlForMissingGroups
     */
    private SetImageUrlForMissingGroups $service;

    public function __construct(SetImageUrlForMissingGroups $service)
    {
        $this->service = $service;
    }

    public static function signature()
    {
        return 'group:fix-images';
    }

    public function handle()
    {
        $this->service->run();
    }
}
