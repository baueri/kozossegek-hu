<?php

namespace Framework\Support;

class DataSet
{
    /**
     * @param array|Collection $items
     * @param $callback
     * @param mixed ...$params
     */
    public static function each($items, $callback, ...$params): void
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

    /**
     * @param array|Collection $items
     * @param $callback
     * @param bool $keepKeys
     * @return array
     */
    public static function map($items, $callback, bool $keepKeys = false): array
    {
        if ($items instanceof Collection) {
            return $items->map($callback, $keepKeys)->toArray();
        }

        $result = [];

        foreach ($items as $key => $item) {
            if (!$keepKeys) {
                $result[] = $callback($item, $key);
            } else {
                $result[$key] = $callback($item, $key);
            }
        }

        return $result;
    }

    public static function filter($items, $callback): array
    {
        if ($items instanceof Collection) {
            return $items->filter($callback)->toArray();
        }

        $result = [];
        foreach ($items as $key => $item) {
            if ($callback($item, $key, $items)) {
                $result[$key] = $item;
            }
        }

        return $result;
    }

    public static function get($item, $key)
    {
        if (is_array($item) && isset($item[$key])) {
            return $item[$key];
        }

        return $item->{$key};
    }

    public static function has($items, $key, $value)
    {
        if($items instanceof Collection) {
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

    /**
     * @param Collection|array $items
     * @param $key
     * @return array
     */
    public static function pluck($items, $key): array
    {
        if ($items instanceof Collection) {
            return $items->pluck($key)->toArray();
        }

        return static::map(
            $items,
            fn($item) => static::getItemValue($item, $key),
        );
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
}
