<?php

namespace Framework\Support;

use ArrayAccess;
use Countable;
use IteratorAggregate;

final class HashMap implements Countable, ArrayAccess, IteratorAggregate
{
    protected array $items = [];

    public function __construct($items = null)
    {
        foreach ((array) $items as $key => $value) {
            $this->items[$this->toKey($key)] = $value;
        }
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$this->toKey($offset)]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$this->toKey($offset)];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->items[$this->toKey($offset)] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$this->toKey($offset)]);
    }

    public function put($key, $value): void
    {
        $this->offsetSet($key, $value);
    }

    public function remove($key): void
    {
        $this->offsetUnset($key);
    }

    public function has($key): bool
    {
        return $this->offsetExists($key);
    }

    public function keys(): self
    {
        return new self(array_keys($this->items));
    }

    private function toKey($key): string
    {
        return HashMapIterator::toKey($key);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function merge($items): HashMap
    {
        $newItems = $this->items;

        foreach ($items as $key => $value) {
            $newItems[$this->toKey($key)] = $value;
        }

        return new self($newItems);
    }

    public function getIterator(): HashMapIterator
    {
        return new HashMapIterator($this->items);
    }
}
