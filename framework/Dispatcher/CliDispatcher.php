<?php


namespace Framework\Dispatcher;


use Framework\Console\ConsoleKernel;
use Framework\Console\Exception\CommandNotFoundException;

class CliDispatcher implements Dispatcher
{

    /**
     * @var ConsoleKernel
     */
    private $kernel;

    /**
     * CliDispatcher constructor.
     * @param ConsoleKernel $kernel
     */
    public function __construct(ConsoleKernel $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @return void
     * @throws CommandNotFoundException
     */
    public function dispatch(): void
    {
        $args = $this->getArgs();

        $file = array_shift($args);

        $signature = array_shift($args);

        $command = $this->kernel->getCommand($signature);

        $command->handle();
    }

    public function handleError($e)
    {
        $this->kernel->handleError($e);
    }

    private function getArgs()
    {
        global $argv;

        $args = $argv;

        return $args;
    }
}
