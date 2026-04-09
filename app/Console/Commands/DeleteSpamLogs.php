<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\SystemEventType;
use App\Repositories\EventLogs;
use Framework\Console\Command;

class DeleteSpamLogs extends Command
{
    public static function signature(): string
    {
        return 'delete-spam-logs';
    }

    public static function description(): string
    {
        return sprintf('spam-mel kapcsolatos logokat (<code>%s</code>) torli a naplobol', SystemEventType::spamLogs()->implode(', '));
    }

    public function handle(): void
    {
        $this->output->info('spam-logok torlese...');
        EventLogs::query()->whereIn('type', SystemEventType::spamLogs())->hardDelete();
        $this->output->success('kesz.');
    }
}
