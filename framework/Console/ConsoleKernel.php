<?php


namespace Framework\Console;


use Framework\Application;
use Framework\Console\BaseCommands\ListCommands;
use Framework\Console\BaseCommands\SiteUp;
use Framework\Console\BaseCommands\SiteDown;
use Framework\Console\Exception\CommandNotFoundException;

class ConsoleKernel
{
    /**
     * @var Command[]
     */
    private $baseCommands = [
        ListCommands::class,
        SiteUp::class,
        SiteDown::class,
    ];

    /**
     * @var Command[]
     */
    protected $commands = [];

    /**
     * @var Application
     */
    private $application;

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
        foreach ($this->getCommands() as $command) {
            if ($command::signature() == $signature) {
                return $this->application->make($command);
            }
        }

        throw new CommandNotFoundException("command not found: $signature");
    }
}
