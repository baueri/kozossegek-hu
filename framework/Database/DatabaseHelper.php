<?php


namespace Framework\Database;


class DatabaseHelper
{
    public static function getQueryWithBindings($query, $bindings)
    {
        foreach ($bindings as $binding) {
            $query = substr($query, 0, strpos($query, '?')) . "'$binding'" . substr($query, strpos($query, '?')+1);
        }

        return $query;
    }
}