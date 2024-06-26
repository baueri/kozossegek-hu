<?php

declare(strict_types=1);

namespace Framework\Console;

use Cake\Utility\Inflector;
use Closure;
use Framework\Application;
use Framework\Console\BaseCommands\ClearCache;
use Framework\Console\BaseCommands\ListCommands;
use Framework\Console\BaseCommands\SiteDown;
use Framework\Console\BaseCommands\SiteUp;
use Framework\Console\Exception\CommandNotFoundException;
use Throwable;

class ConsoleKernel
{
    /**
     * @var Command[]|string[]
     */
    private array $commands;

    public function __construct(
        private readonly Application $application
    ) {
        $this->withCommand([
            ListCommands::class,
            SiteUp::class,
            SiteDown::class,
            ClearCache::class
        ]);
    }

    public function loadCommands(string $path): static
    {
        $files = rglob(ROOT . ltrim( $path, '/') . '/*.php');
        $commands = [];
        foreach ($files as $file) {
            $file = str_replace(ROOT, '', $file);
            $commands[] = '\\' . mb_ucfirst(str_replace(['/', '.php'], ['\\', ''], $file));
        }

        $this->withCommand($commands);

        return $this;
    }

    /**
     * @throws CommandNotFoundException
     */
    public function handle()
    {
        $args = $this->getArgs();

        $signature = array_shift($args);
        $command = $this->getCommand($signature);

        try {
            if (is_callable($command)) {
                return $command(...$this->application->getDependencies($command)) ?? Command::SUCCESS;
            }

            $command->withArgs($args);
            return $command->handle() ?? Command::SUCCESS;
        } catch (Throwable $e) {
            app()->handleError($e);
            return Command::FAILURE;
        }
    }

    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * @template T of Command
     * @param class-string<T>[]|class-string<T> $command
     * @param $handler
     * @return $this
     */
    public function withCommand(array|string $command, $handler = null): static
    {
        if (is_array($command)) {
            foreach ($command as $singleCommand) {
                $this->commands[$singleCommand::signature()] = $singleCommand;
            }
        } elseif ($handler) {
            $this->commands[$command] = $handler;
        } else {
            $this->commands[$command::signature()] = $command;
        }

        return $this;
    }

    /**ar
     * @throws CommandNotFoundException
     */
    public function getCommand(?string $signature): Command|Closure
    {
        if (!$signature) {
            return $this->application->make(ListCommands::class);
        }

        foreach ($this->getCommands() as $registeredSignature => $command) {
            if ($registeredSignature == $signature) {
                return $command instanceof Closure ? $command : $this->application->get($command);
            }
        }

        throw new CommandNotFoundException("command not found: $signature");
    }

    public function getArgs()
    {
        global $argv;

        $args = $argv;

        array_shift($args);

        return $args;
    }
}
