<?php

declare(strict_types=1);

namespace App\Console\Commands\Cron;

use App\Console\Commands\DeleteSpamLogs;
use App\Console\Commands\GroupActivityConfirmNotifier;
use App\Console\Commands\InactivateUnconfirmedGroups;
use Framework\Console\BaseCommands\CronCommand;

class MonthlyCron extends CronCommand
{
    public static function signature(): string
    {
        return 'cron:monthly';
    }

    public function jobs(): array
    {
        return [
            resolve(GroupActivityConfirmNotifier::class),
            resolve(InactivateUnconfirmedGroups::class),
            resolve(DeleteSpamLogs::class)->silent()
        ];
    }
}
