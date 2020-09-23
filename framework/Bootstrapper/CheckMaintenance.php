<?php


namespace Framework\Bootstrapper;


use Framework\Bootstrapper;
use Framework\Http\HttpKernel;

class CheckMaintenance implements Bootstrapper
{
    /**
     * @var HttpKernel
     */
    private $kernel;

    /**
     * CheckMaintenance constructor.
     * @param HttpKernel $kernel
     */
    public function __construct(HttpKernel $kernel)
    {
        $this->kernel = $kernel;
    }

    public function boot()
    {

        if (file_exists(ROOT . '.maintenance')) {
            $this->kernel->handleMaintenance();
            exit;
        }
    }
}