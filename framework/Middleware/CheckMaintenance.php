<?php


namespace Framework\Middleware;


use Framework\Bootstrapper;
use Framework\Http\HttpKernel;
use Framework\Maintenance;

use Framework\Middleware\Middleware;

class CheckMaintenance implements Middleware
{
    /**
     * @var HttpKernel
     */
    private $kernel;

   /**
    * @var Maintenance
    */
    private $maintenance;

    /**
     * CheckMaintenance constructor.
     * @param HttpKernel $kernel
     */
    public function __construct(HttpKernel $kernel, Maintenance $maintenance)
    {
        $this->kernel = $kernel;
        $this->maintenance = $maintenance;
    }

    public function handle(): void
    {

        if ($this->maintenance->isMaintenanceOn()) {
            $this->kernel->handleMaintenance();
            exit;
        }
    }
}
