<?php

namespace Framework\Console;

interface Command
{
    public static function signature(): string;

    public function handle(): void;
}
