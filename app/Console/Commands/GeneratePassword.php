<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Framework\Console\Color;
use Framework\Console\Command;
use Framework\Support\Password;

class GeneratePassword extends Command
{
    public function description(): string
    {
        return 'generates a password';
    }

    public static function signature(): string
    {
        return 'password:generate';
    }

    public function handle(): void
    {
        $length = (int) $this->getOption('l') ?: null;
        $this->output->writeln(Password::generate($length)->password, Color::green);
    }
}
