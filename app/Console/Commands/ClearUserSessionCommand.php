<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\SystemAdministration\ClearUserSession;
use Framework\Console\Command;
use Framework\Console\Out;

class ClearUserSessionCommand extends Command
{
    public function __construct(
        private readonly ClearUserSession $service
    ) {
    }

    public static function signature(): string
    {
        return 'clear:session';
    }

    public function handle(): void
    {
        if($this->service->run()) {
            Out::success('user session cleared.');
        } else {
            Out::error('could not delete user session.');
        }
    }
}
