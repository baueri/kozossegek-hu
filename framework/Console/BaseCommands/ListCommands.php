<?php

declare(strict_types=1);

namespace Framework\Console\BaseCommands;

use Framework\Console\Command;
use Framework\Console\ConsoleKernel;
use jc21\CliTable;

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

    public static function description(): string
    {
        return 'listazza a futtathato commandokat';
    }

    public function handle(): void
    {
        $table = new CliTable();
        $this->output->heading('list of available commands');
        $table->setHeaderColor('cyan');
        $table->addField('signature', 'signature', false, 'yellow');
        $table->addField('description', 'description', false, 'white');
        $table->injectData(array_map(fn ($command) => ['signature' => $command::signature(), 'description' => strip_tags($command::description())], $this->kernel->getCommands()));
        $table->display();
    }
}
