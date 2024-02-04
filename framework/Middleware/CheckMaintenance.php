<?php


namespace Framework\Middleware;

use Framework\Kernel;
use Framework\Maintenance;

use Framework\Middleware\Middleware;

class CheckMaintenance implements Middleware
{
    public function __construct(
        protected readonly Kernel $kernel,
        protected readonly Maintenance $maintenance
    ) {
    }

    public function handle(): void
    {

        if ($this->maintenance->isMaintenanceOn()) {
            $this->kernel->handleMaintenance();
            exit;
        }
    }
}
