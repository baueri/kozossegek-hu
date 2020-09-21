<?php


namespace Framework\Console\BaseCommands;


use Framework\Console\Command;
use Framework\Console\ConsoleKernel;
use Framework\Console\Out;

class ListCommands implements Command
{

    /**
     * @var ConsoleKernel
     */
    private $kernel;

    /**
     * @var Out
     */
    private $out;

    /**
     * ListCommands constructor.
     * @param ConsoleKernel $kernel
     * @param Out $out
     */
    public function __construct(ConsoleKernel $kernel, Out $out)
    {
        $this->kernel = $kernel;
        $this->out = $out;
    }

    public static function signature()
    {
        return 'list';
    }

    public function handle()
    {
        $this->out->heading('list of available commands');
        foreach ($this->kernel->getCommands() as $command) {
            $this->out->writeln($command::signature());
        }
    }
}