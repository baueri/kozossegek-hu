<?php


namespace Framework\Console;


interface Command
{
    public static function signature();

    public function handle();
}