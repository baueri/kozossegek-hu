<?php

namespace Framework\Support;

use ArrayIterator;

class HashMapIterator extends ArrayIterator
{
    public function key(): string|int|null
    {
        return static::toKey(parent::key());
    }

    public static function toKey($key): string
    {
        if (is_numeric($key)) {
            return (string) $key;
        }

        if (is_object($key)) {
            return spl_object_hash($key);
        }

        return $key;
    }
}
