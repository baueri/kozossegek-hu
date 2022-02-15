<?php

namespace Framework\Console;

use Framework\Application;
use Framework\Console\BaseCommands\ClearCache;
use Framework\Console\BaseCommands\ListCommands;
use Framework\Console\BaseCommands\SiteUp;
use Framework\Console\BaseCommands\SiteDown;
use Framework\Console\Exception\CommandNotFoundException;
use Framework\Kernel;

class ConsoleKernel implements Kernel
{
    /**
     * @var Command[]|string[]
     */
    private array $baseCommands = [
        ListCommands::class,
        SiteUp::class,
        SiteDown::class,
        ClearCache::class
    ];

    /**
     * @var Command[]|string[]
     */
    protected array $commands = [];

    public function __construct(private Application $application)
    {
    }

    /**
     * @return Command[]
     */
    public function getCommands(): array
    {
        return array_merge($this->commands, $this->baseCommands);
    }

    public function getCommand(?string $signature)
    {
        if (!$signature) {
            return $this->application->make(ListCommands::class);
        }

        foreach ($this->getCommands() as $command) {
            if ($command::signature() == $signature) {
                return $this->application->make($command);
            }
        }

        throw new CommandNotFoundException("command not found: $signature");
    }

    public function handleError($error): void
    {
        Out::error('HIBA');
        Out::error($error->getMessage());
    }
}
