<?php

namespace Framework\Support;

use ArrayAccess;
use ArrayIterator;
use Closure;
use Countable;
use IteratorAggregate;

class Collection implements ArrayAccess, Countable, IteratorAggregate
{
    protected array $items = [];

    public function __construct(?array $items = [])
    {
        if ($items) {
            $this->items = $items;
        }
    }

    /**
     * @param mixed $item
     * @param mixed $key
     */
    public function push($item, $key = null): self
    {
        if (!$key) {
            array_push($this->items, $item);
        } else {
            if (is_numeric($key)) {
                array_splice($this->items, $key, 0, array($key => $item));
            } else {
                $this->items[$key][] = $item;
            }
        }

        return $this;
    }

    public function set($key, $item): self
    {
        $this->items[$key] = $item;

        return $this;
    }

    public function pop()
    {
        return array_pop($this->items);
    }

    public function keyBy($key): self
    {
        $items = [];
        foreach ($this->items as $item) {
            $items[static::getItemVal($item, $key)] = $item;
        }

        return new self($items);
    }


    /**
     * @param Closure|string|null $callback
     * @param null $default
     */
    public function first($callback = null, $default = null)
    {
        if (!$callback) {
            return reset($this->items) ?: $default;
        }

        foreach ($this->items as $key => $val) {
            if ((is_callable($callback) && $callback($val, $key)) || (bool)static::getItemVal($val, $callback)) {
                return $val;
            }
        }
        return $default;
    }

    private static function getItemVal($item, $key)
    {
        if (is_array($item) && isset($item[$key])) {
            return $item[$key];
        }

        return $item->{$key};
    }

    public function slice(int $length, int $offset = 0, bool $preserve_keys = false): self
    {
        return new self(array_slice($this->items, $offset, $length, $preserve_keys));
    }

    public function last()
    {
        return end($this->items);
    }

    public function random()
    {
        return Arr::random($this->items);
    }

    public function each(Closure $func): self
    {
        Arr::each($this->items, $func, $this);

        return $this;
    }

    public function chunk(int $size, bool $preserve_keys = false): self
    {
        return new self(array_chunk($this->items, $size, $preserve_keys));
    }

    public function diff(array $array): Collection
    {
        return new self(array_diff($this->items, $array));
    }

    public function filter($func = null): self
    {
        if (!$func) {
            return new self(array_filter($this->items));
        }

        return new self(Arr::filter($this->items, $func));
    }

    /**
     * @param callable $func
     * @return self
     */
    public function reject(callable $func): self
    {
        $result = [];

        foreach ($this->items as $item) {
            if (!$func($item)) {
                $result[] = $item;
            }
        }

        return new self($result);
    }

    public function contains($value): bool
    {
        if (!is_callable($value)) {
            return in_array($value, $this->items);
        }

        foreach ($this->items as $key => $item) {
            if ($value($key, $item)) {
                return true;
            }
        }
        return false;
    }

    public function sum($key = null): int
    {
        if ($key) {
            if (is_callable($key)) {
                return array_sum(array_map($key, $this->items));
            }
            return array_sum($this->items[$key]);
        }

        return array_sum($this->items);
    }

    public function exists($key): bool
    {
        return isset($this->items[$key]);
    }

    public function keys(): array
    {
        return array_keys($this->items);
    }

    public function serialize(): string
    {
        return serialize($this->items);
    }

    public function toJson($options = 0, $depth = 512): string
    {
        return (string) json_encode($this->items, $options, $depth);
    }

    public function prettyJson(): string
    {
        return $this->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function sort(): self
    {
        sort($this->items);
        return $this;
    }

    public function ksort(): self
    {
        ksort($this->items);
        return $this;
    }

    public function separate($key): self
    {
        $result = [];
        foreach ($this->items as $item) {
            ($result[$item[$key]] ??= new self())->push($item);
        }

        return new self($result);
    }

    public function reduce(callable $func, $initial)
    {
        $accumulator = $initial;

        foreach ($this->items as $key => $item) {
            $accumulator = $func($accumulator, $item, $key);
        }

        return $accumulator;
    }

    public function implode($glue): string
    {
        return implode($glue, $this->items);
    }

    public function reverse(bool $preserve_keys = true): self
    {
        return new self(array_reverse($this->items, $preserve_keys));
    }

    public function values(): self
    {
        return new self(array_values($this->items));
    }

    public function pluck($key): self
    {
        return new self(Arr::pluck($this->items, $key));
    }

    public function map($func, bool $keepKeys = false): self
    {
        return new self(Arr::map($this->items, $func, $keepKeys));
    }

    public function unique($key = null): self
    {
        if (!$key) {
            return new self(array_unique($this->items));
        }

        $filtered = [];
        foreach ($this->items as $key => $value) {
            if (!isset($filtered[$key])) {
                $filtered[$key] = $value;
            }
        }

        return new self($filtered);
    }

    public function distinct($key = null): self
    {
        return $this->unique($key)->pluck($key);
    }


    public function zip(array $array): self
    {
        $result = [];
        $collection = new self($array);
        foreach ($this->items as $key => $item) {
            $result[] = [$item, $collection->get($key)];
        }
        return new self($result);
    }

    /**
     * @param mixed $key
     * @param mixed $default
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        if (isset($this->items[$key])) {
            return $this->items[$key];
        }

        return $default;
    }

    public function merge($toMerge): self
    {
        $array = $toMerge instanceof self ? $toMerge->all() : $toMerge;

        return new self(array_merge($this->items, $array));
    }

    public function all(): array
    {
        return $this->items;
    }

    public function shuffle(): self
    {
        $items = $this->items;
        shuffle($items);

        return new self($items);
    }

    public function dump(): void
    {
        d($this->items);
    }

    public function replace($value, $replaceTo): self
    {
        return $this->map(fn ($item) => $item === $value ? $replaceTo : $item);
    }

    public function find($val, $returnKey = false, $default = null)
    {
        if (!is_callable($val)) {
            return array_search($val, $this->items) ?: $default;
        } else {
            foreach ($this->items as $key => $item) {
                if ($val($item, $key)) {
                    return !$returnKey ? $item : $key;
                }
            }
            return $default;
        }
    }

    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->items);
    }

    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    public function loop()
    {
        $item = current($this->items);

        next($this->items);

        return $item;
    }

    public function reset()
    {
        return reset($this->items);
    }

    public function key()
    {
        return key($this->items);
    }

    public function next()
    {
        return next($this->items);
    }

    public function prev()
    {
        return prev($this->items);
    }

    public function __toString(): string
    {
        return $this->toJson(JSON_UNESCAPED_UNICODE);
    }

    public function only(...$keys): array
    {
        $values = [];

        if (is_array($keys[0])) {
            $keys = $keys[0];
        }

        foreach ($keys as $key) {
            if ($this->exists($key)) {
                $values[$key] = $this->get($key);
            }
        }

        return $values;
    }

    public function isEmpty(...$except): bool
    {
        if ($except) {
            return $this->except(...$except)->isEmpty();
        }

        return empty($this->items);
    }

    public function isNotEmpty(...$except): bool
    {
        return !$this->isEmpty(...$except);
    }

    public function except(...$keys): self
    {
        return $this->filter(function ($item, $key) use ($keys) {
            return !in_array($key, $keys);
        });
    }

    public function as(string $abstraction): self
    {
        return $this->map(function ($item) use ($abstraction) {
            return app()->make($abstraction, $item);
        });
    }

    public function groupBy($key): self
    {
        $grouped = [];

        foreach ($this->items as $item) {
            $grouped[static::getItemVal($item, $key)][] = $item;
        }

        return new self($grouped);
    }

    public function collect(): self
    {
        return new self($this->items);
    }

    public function has($key, $value): bool
    {
        return Arr::has($this->items, $key, $value);
    }
}
