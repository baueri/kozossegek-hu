<?php

namespace App\Console\Commands;

use App\Services\SystemAdministration\ClearUserSession;
use App\Services\SystemAdministration\ForceDeleteDeletedChurchGroups;
use App\Services\SystemAdministration\ForceDeleteDeletedUsers;
use Framework\Console\Command;

class DailyCron implements Command
{

    private ClearUserSession $clearUserSession;

    private ForceDeleteDeletedUsers $forceDeleteDeletedUsers;

    private ForceDeleteDeletedChurchGroups $forceDeleteDeletedChurchGroups;

    public function __construct(
        ClearUserSession $clearUserSession,
        ForceDeleteDeletedUsers $forceDeleteDeletedUsers,
        ForceDeleteDeletedChurchGroups $forceDeleteDeletedChurchGroups
    ) {
        $this->clearUserSession = $clearUserSession;
        $this->forceDeleteDeletedUsers = $forceDeleteDeletedUsers;
        $this->forceDeleteDeletedChurchGroups = $forceDeleteDeletedChurchGroups;
    }

    public static function signature()
    {
        return 'cron:daily';
    }

    public function handle()
    {
        $this->clearUserSession->run();
    }
}
