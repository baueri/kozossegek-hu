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
    public static function pluck($items, $key)
    {
        if ($items instanceof Collection) {
            return $items->pluck($key)->toArray();
        }

        return static::map(
            $items,
            fn($item) => is_object($item) ? $item->{$key} : $item[$key],
        );
    }
}
