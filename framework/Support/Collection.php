<?php

namespace Framework\Support;

use ArrayAccess;
use ArrayIterator;
use Closure;
use Countable;
use IteratorAggregate;

/**
 * @template T
 * @property-read T|CollectionProxy $map
 * @property-read T|CollectionProxy $each
 * @property-read T|CollectionProxy $filter
 * @property-read T|CollectionProxy $reject
 */
class Collection implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * @phpstan-var T[]
     */
    protected array $items = [];

    /**
     * @param array<T>|null|T[]|T $items
     */
    public function __construct(mixed $items = null)
    {
        if ($items instanceof Collection) {
            $this->items = $items->all();
            return;
        }

        $this->items = Arr::wrap($items);
    }

    public function put($item, int|string|null $key = null): self
    {
        if (!$key) {
            $this->items[] = $item;
        } else {
            if (is_numeric($key)) {
                array_splice($this->items, $key, 0, array($key => $item));
            } else {
                $this->items[$key][] = $item;
            }
        }

        return $this;
    }

    public function push(...$items): static
    {
        foreach ($items as $item) {
            $this->items[] = $item;
        }

        return $this;
    }

    public function set($key, $item): self
    {
        $this->items[$key] = $item;

        return $this;
    }

    /**
     * @phpstan-return T|null
     */
    public function pop()
    {
        return array_pop($this->items);
    }

    /**
     * @phpstan-return self<T>
     */
    public function keyBy(int|string|Closure $by): self
    {
        $items = [];
        foreach ($this->items as $item) {
            $key = $by instanceof Closure ? $by($item) : static::getItemVal($item, $by);
            $items[$key] = $item;
        }

        return new self($items);
    }


    /**
     * @phpstan-return T|null
     */
    public function first(Closure|string $callback = null, $default = null)
    {
        if (!$callback) {
            return reset($this->items) ?: $default;
        }

        foreach ($this->items as $key => $val) {
            if (is_callable($callback)) {
                if ($callback($val, $key)) {
                    return $val;
                }
            } elseif (static::getItemVal($val, $callback)) {
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

    /**
     * @phpstan-return T
     */
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

    public function take(int $limit)
    {
        return new self(array_slice($this->items, 0, $limit));
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

    public function filterByKey($func): self
    {
        return new self(Arr::filterByKey($this->items, $func));
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

    public function containsAny(array $values): bool
    {
        foreach ($this->items as $item) {
            if (in_array($item, $values)) {
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

    public function sort($by = null, string $order = 'asc'): self
    {
        if ($by) {
            uasort(
                $this->items,
                $by instanceof Closure ? $by :
                    fn ($a, $b) => static::getItemVal($order === 'asc' ? $a : $b, $by) <=> static::getItemVal($order === 'asc' ? $b : $a, $by)
            );
            return $this;
        }

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

    public function implode($value, $glue = null): string
    {
        if (is_null($glue)) {
            return implode($value, $this->items);
        }

        return $this->pluck($value)->implode($glue);
    }

    public function toList(string $delimiter = '-'): string
    {
        return "{$delimiter} {$this->implode("\n{$delimiter} ")}";
    }

    public function reverse(bool $preserve_keys = true): self
    {
        return new self(array_reverse($this->items, $preserve_keys));
    }

    public function values(): self
    {
        return new self(array_values($this->items));
    }

    public function pluck($key, $keyBy = null): self
    {
        return new self(Arr::pluck($this->items, $key, $keyBy));
    }

    public function map($func, bool $keepKeys = true): self
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

    public function get(int|string $key, $default = null)
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

    public function offsetGet($offset): mixed
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value): void
    {
        if ($offset === null) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset($offset): void
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

    public function key(): int|string|null
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

    public function unshift($item): static
    {
        array_unshift($this->items, $item);
        return $this;
    }

    public function __toString(): string
    {
        return $this->toJson(JSON_UNESCAPED_UNICODE);
    }

    public function only(...$keys): array
    {
        if (is_array($keys[0])) {
            $keys = $keys[0];
        }

        return Arr::only($this->items, $keys);
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
            if (enum_exists($abstraction)) {
                return $abstraction::from($item);
            }
            return app()->make($abstraction, [$item]);
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

    public function has($key, $value = null): bool
    {
        return Arr::has($this->items, $key, $value);
    }

    public static function fromList(?string $text, string $separator = ','): Collection
    {
        return new self(Arr::fromList($text, $separator));
    }

    public static function fromJson(): static
    {
        return new self(json_decode(...func_get_args()));
    }

    public static function fromJsonFile(string $fileName, ...$params): static
    {
        return static::fromJson(file_get_contents($fileName), ...$params);
    }

    public function __get(string $name)
    {
        return new CollectionProxy($this, $name);
    }

    public function dd(): never
    {
        dd($this->items);
    }

    public function firstNonEmpty(Closure $callback)
    {
        foreach ($this->items as $item) {
            if ($val = $callback($item)) {
                return $val;
            }
        }

        return null;
    }

    public function buildQuery(): string
    {
        return http_build_query($this->items);
    }
}
