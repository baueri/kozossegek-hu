<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Framework\Console\Color;
use Framework\Console\Command;
use Framework\Support\Password;

class GeneratePassword extends Command
{
    public static function description(): string
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
        $password = Password::generate($length);

        $this->output->writeln('password: ', Color::blue)
            ->writeln("{$password->password}")
            ->writeln('hash: ', Color::blue)
            ->writeln("{$password->hash}");
    }
}
