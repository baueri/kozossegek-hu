<?php

namespace App\Console\Commands;

use Framework\Console\Command;

class ClearThrottle extends Command
{
    public static function signature(): string
    {
        return 'throttle:clear';
    }

    public function handle(): void
    {
        $this->output->writeln('Clearing throttle...');

        builder('throttle_request')->where('expires_at', '<', now())->delete();

        $this->output->success('Throttle cleared successfully');
    }
}