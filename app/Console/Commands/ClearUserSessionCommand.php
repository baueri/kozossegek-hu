<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\SystemAdministration\ClearUserSession;
use Framework\Console\Command;

class ClearUserSessionCommand extends Command
{
    public function __construct(
        private readonly ClearUserSession $service
    ) {
        parent::__construct();
    }

    public static function signature(): string
    {
        return 'session:clear';
    }

    public static function description(): string
    {
        return 'Törli az egy napnál régebbi session sorokat a user_session táblából.';
    }

    public function handle(): void
    {
        $this->service->run();
        $this->output->success('user session cleared.');
    }
}
