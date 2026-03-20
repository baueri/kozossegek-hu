<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\EventType;
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
        return sprintf('spam-mel kapcsolatos logokat (<code>%s</code>) torli a naplobol', EventType::spamLogs()->implode(', '));
    }

    public function handle(): void
    {
        $this->output->info('spam-logok torlese...');
        EventLogs::query()->whereIn('type', EventType::spamLogs())->hardDelete();
        $this->output->success('kesz.');
    }
}
