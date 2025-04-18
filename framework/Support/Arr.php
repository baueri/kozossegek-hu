<?php

namespace Framework\Support;

use Closure;

class Arr
{
    public static function each(array|Collection $items, callable|Closure $callback, ...$params): void
    {
        if ($items instanceof Collection) {
            $items->each($callback);
        } else {
            foreach ($items as $key => $item) {
                if ($callback($item, $key, ...$params) === false) {
                    break;
                }
            }
        }
    }

    public static function map(array|Collection $items, callable|string $callback, bool $keepKeys = false): array
    {
        if ($items instanceof Collection) {
            return $items->map($callback, $keepKeys)->all();
        }

        $result = [];

        foreach ($items as $key => $item) {
            if (is_object($item) && is_string($callback) && method_exists($item, $callback)) {
                $value = $item->{$callback}();
            } else {
                $value = $callback($item, $key);
            }
            if (!$keepKeys) {
                $result[] = $value;
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    public static function filter($items, $callback = null, bool $byKey = false): array
    {
        if ($items instanceof Collection) {
            return $items->filter($callback)->all();
        }

        $result = [];

        foreach ($items as $key => $item) {
            if ($byKey ? $callback($key) : $callback($item, $key, $items)) {
                $result[$key] = $item;
            }
        }

        return $result;
    }

    public static function filterByKey($items, $callback): array
    {
        return static::filter($items, $callback, true);
    }

    public static function get($item, $key, $default = null)
    {
        if (is_array($item) && isset($item[$key])) {
            return $item[$key];
        }

        return $item->{$key} ?? $default;
    }

    public static function has($items, $key, $value = null): bool
    {
        if (is_null($value)) {
            return array_key_exists($key, $items);
        }

        if ($items instanceof Collection) {
            return $items->has($key, static::getItemValue($value, $key));
        }

        foreach ($items as $item) {
            if (static::getItemValue($item, $key) == static::getItemValue($value, $key)) {
                return true;
            }
        }

        return false;
    }

    public static function random($items)
    {
        if ($items instanceof Collection) {
            return $items->random();
        }

        return $items[array_rand($items)];
    }

    public static function pluck($items, $key, $keyBy = null): array
    {
        if ($items instanceof Collection) {
            return $items->pluck($key, $keyBy)->all();
        }

        $return = [];
        foreach ($items as $item) {
            if ($keyBy) {
                $return[static::getItemValue($item, $keyBy)] = static::getItemValue($item, $key);
            } else {
                $return[] = static::getItemValue($item, $key);
            }
        }

        return $return;
    }

    public static function only(array $items,$only): array
    {
        $only = Arr::wrap($only);
        return static::filterByKey($items, fn ($key) => in_array($key, $only));
    }

    public static function getItemValue($item, $key = null)
    {
        if (!$key) {
            return $item;
        }

        if (is_array($item) && isset($item[$key])) {
            return $item[$key];
        }

        if (is_object($item)) {
            return $item->{$key};
        }

        return $item;
    }

    public static function sum(array $results, string $column = null): float|int
    {
        if (is_numeric(Arr::first($results))) {
            return array_sum($results);
        }

        return static::sum(static::pluck($results, $column));
    }

    public static function first(array $results)
    {
        return $results[key($results)];
    }

    public static function wrap($value): array
    {
        if (is_null($value)) {
            return [];
        }

        if (is_object($value) || is_callable($value)) {
            return [$value];
        }

        return (array) $value;
    }

    public static function fromList(?string $text, string $separator = ','): array
    {
        $separator ??= ',';

        return match ($text) {
            null, '' => [],
            default => explode($separator, $text)
        };
    }

    public static function except(array $list, array|string|int $except): array
    {
        $except = (array) $except;
        foreach ($list as $key => $item) {
            if (in_array($key, $except)) {
                unset($list[$key]);
            }
        }

        return $list;
    }

    public static function flatten(array $array): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, static::flatten($value));
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}
