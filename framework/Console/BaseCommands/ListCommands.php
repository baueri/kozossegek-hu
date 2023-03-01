<?php

declare(strict_types=1);

namespace Framework\Console\BaseCommands;

use Framework\Console\Command;
use Framework\Console\ConsoleKernel;

class ListCommands extends Command
{
    private ConsoleKernel $kernel;

    public function __construct(ConsoleKernel $kernel)
    {
        $this->kernel = $kernel;
        parent::__construct();
    }

    public static function signature(): string
    {
        return 'list';
    }

    public function handle(): void
    {
        $this->output->heading('list of available commands');
        foreach ($this->kernel->getCommands() as $command) {
            $this->output->writeln($command::signature());
        }
    }
}
