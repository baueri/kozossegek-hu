<?php

namespace App\Services;

use Exception;

final class MileStone
{
    private static array $mileStones = [];

    public static function measure(string $name, string $title = ''): void
    {
        if (!debugbar()->enabled()) {
            return;
        }

        if (isset(self::$mileStones[$name])) {
            throw new Exception("Measuring {$name} already started");
        }

        self::$mileStones[$name] = [
            'title' => $title,
            'start' => microtime(true)
        ];
    }

    public static function endMeasure(string $name)
    {
        if (!debugbar()->enabled()) {
            return;
        }

        self::$mileStones[$name]['end'] = microtime(true);
    }

    public static function get(): array
    {
        return self::$mileStones;
    }
}