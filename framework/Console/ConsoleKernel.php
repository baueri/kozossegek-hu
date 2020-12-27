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

    /**
     * @var Application
     */
    private Application $application;

    /**
     * ConsoleKernel constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @return Command[]
     */
    public function getCommands()
    {
        return array_merge($this->commands, $this->baseCommands);
    }

    /**
     * @param $signature
     * @return Command
     * @throws CommandNotFoundException
     */
    public function getCommand($signature)
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

    public function handleError($error)
    {
        Out::error('HIBA');
        Out::error($error->getMessage());
    }
}
