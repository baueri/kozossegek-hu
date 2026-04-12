<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Baueri\Mint\MintCli;
use Baueri\Mint\MintView;
use Framework\Console\Command;

class MintCommand extends Command
{
    public function __construct(private readonly MintView $view)
    {
        parent::__construct();
    }

    public static function signature(): string
    {
        return 'mint';
    }

    public static function description(): string
    {
        return 'Mint template engine utilities: clear, compile, watch.';
    }

    public function handle(): int
    {
        $action = $this->getArguments()[0] ?? null;

        $cli = new MintCli(fn(string $line) => $this->output->writeln($line));

        return match ($action) {
            'clear'   => $this->runClear($cli),
            'compile' => $this->runCompile($cli),
            'watch'   => $this->runWatch($cli),
            default   => $this->showUsage(),
        };
    }

    private function runClear(MintCli $cli): int
    {
        $cli->clear($this->view->cache);

        return self::SUCCESS;
    }

    private function runCompile(MintCli $cli): int
    {
        $cli->compileAll($this->view->compiler, $this->view->viewsPath, $this->view->cache);

        return self::SUCCESS;
    }

    private function runWatch(MintCli $cli): int
    {
        $pollMs = (int) ($this->getOption('poll') ?: 500);

        $cli->watch($this->view->compiler, $this->view->viewsPath, $this->view->cache, pollIntervalMs: $pollMs);

        return self::SUCCESS;
    }

    private function showUsage(): int
    {
        $this->output->writeln('Usage: ./console mint <action> [options]');
        $this->output->writeln('');
        $this->output->writeln('Actions:');
        $this->output->writeln('  clear    Remove all compiled templates from the cache');
        $this->output->writeln('  compile  Pre-compile all templates');
        $this->output->writeln('  watch    Watch for template changes and recompile automatically');
        $this->output->writeln('');
        $this->output->writeln('Options:');
        $this->output->writeln('  --poll=<ms>  Polling interval in milliseconds for watch (default: 500)');

        return self::FAILURE;
    }
}
