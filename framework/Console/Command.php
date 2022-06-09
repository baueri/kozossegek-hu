<?php

namespace Framework\Console;

interface Command
{
    public const SUCCESS = 0;
    public const FAILURE = 1;

    public static function signature(): string;

    public function handle();
}
