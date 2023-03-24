<?php

namespace Framework\Dispatcher;

use Exception;
use Framework\Console\Command;
use Framework\Console\ConsoleKernel;
use Framework\Console\Exception\CommandNotFoundException;

class CliDispatcher implements Dispatcher
{
    private ConsoleKernel $kernel;

    public function __construct(ConsoleKernel $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @throws CommandNotFoundException
     */
    public function dispatch(): int
    {
        $args = $this->getArgs();

        array_shift($args);

        $signature = array_shift($args);

        $command = $this->kernel->getCommand($signature);

        $command->withArgs($args);

        try {
            return $command->handle() ?? Command::SUCCESS;
        } catch (Exception $e) {
            $this->handleError($e);
            return Command::FAILURE;
        }
    }

    public function handleError($e): void
    {
        $this->kernel->handleError($e);
    }

    private function getArgs()
    {
        global $argv;

        return $argv;
    }
}
