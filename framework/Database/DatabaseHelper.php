<?php

declare(strict_types=1);

namespace Framework\Database;

use BackedEnum;
use UnitEnum;

class DatabaseHelper
{
    public static function getQueryWithBindings($query, $bindings)
    {
        foreach ($bindings as $binding) {
            if ($binding instanceof UnitEnum) {
                $binding = $binding instanceof BackedEnum ? $binding->value : $binding->name;
            }
            $query = substr($query, 0, strpos($query, '?')) . "'$binding'" . substr($query, strpos($query, '?')+1);
        }

        return $query;
    }
}
