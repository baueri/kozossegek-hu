<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\QueryBuilders\UserTokens;
use Framework\Console\Command;

class DeleteExpiredUserTokens extends Command
{
    public static function signature(): string
    {
        return 'usertoken:delete-expired';
    }

    public static function description(): string
    {
        return 'Torli a lejart user tokeneket.';
    }

    public function handle(): void
    {
        $this->output->info('Deleting expired user tokens...');

        UserTokens::query()->wherePast('expires_at')->delete();

        $this->output->success('Done');
    }
}
