<?php

namespace Framework\Support;

use ArrayAccess;
use ArrayIterator;
use Closure;
use Countable;
use Exception;
use IteratorAggregate;

class Collection implements ArrayAccess, Countable, IteratorAggregate
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * Collection constructor.
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        if (is_string($items)) {
            $items = [$items];
        }

        $this->items = $items;
    }

    /**
     * @param int $length
     * @param array $items
     * @return static
     */
    public static function createAndFill($length, $items)
    {
        return self::create()
            ->fill($length, $items);
    }

    /**
     * @param int $length
     * @param mixed $items
     * @return static
     */
    public function fill($length, $items)
    {
        for ($i = 1; $i <= $length; $i++) {
            if (is_array($items)) {
                $this->items[] = $items[$i];
            } elseif (is_callable($items)) {
                $this->items[] = $items($i);
            } else {
                $this->items[] = $items;
            }
        }
        return $this;
    }

    /**
     * @param array $items
     * @return static
     */
    public static function create($items = [])
    {
        return new static($items);
    }

    /**
     * Places an item to the end of collection or to a specific position
     * @param mixed $item
     * @param mixed $key
     * @return Collection
     */
    public function push($item, $key = null)
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

    /**
     * @param $key
     * @param $item
     */
    public function set($key, $item)
    {
        $this->items[$key] = $item;
        
        return $this;
    }

    /**
     * Removes last item of collection
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->items);
    }

    /**
     * @param Closure|null $callback
     * @param null $default
     * @return mixed
     */
    public function first(Closure $callback = null, $default = null)
    {
        if (!$callback) {
            return reset($this->items) ?: $default;
        } else {
            foreach ($this->items as $key => $val) {
                if ($callback($val, $key)) {
                    return $val;
                }
            }
            return $default;
        }
    }

    /**
     * @param int $length
     * @param int $offset
     * @param bool $preserve_keys
     * @return Collection
     */
    public function slice($length, $offset = 0, $preserve_keys = false)
    {
        return static::create(array_slice($this->items, $offset, $length, $preserve_keys));
    }

    /**
     * Returns last element of collection
     * @return mixed
     */
    public function last()
    {
        return end($this->items);
    }

    /**
     * Returns a random element from the collections
     * @return mixed
     */
    public function random()
    {
        return $this->items[array_rand($this->items)];
    }

    /**
     * Runs a callback on each item
     *
     * @param Closure $func
     * @return void
     */
    public function each(Closure $func)
    {
        foreach ($this->items as $key => $item) {
            if ($func($item, $key) === false) {
                break;
            }
        }
    }

    /**
     * Chunks the collection
     * @param integer $size
     * @param bool $preserve_keys
     * @return Collection
     */
    public function chunk($size, $preserve_keys = false)
    {
        return new static(array_chunk($this->items, $size, $preserve_keys));
    }

    /**
     * Returns the difference between the collection and the array given in param
     *
     * @param array $array
     * @return static
     */
    public function diff(array $array)
    {
        return new static(array_diff($this->items, $array));
    }

    /**
     * Filters collection by give callback
     * @param callback $func
     * @return static;
     */
    public function filter($func)
    {
        $result = [];

        foreach ($this->items as $key => $item) {
            if ($func($item, $key)) {
                $result[$key] = $item;
            }
        }

        return new static($result);
    }

    /**
     * Reverse of the filter method
     *
     * @param callback $func
     * @return static;
     */
    public function reject($func)
    {
        $result = [];

        foreach ($this->items as $item) {
            if (!$func($item)) {
                $result[] = $item;
            }
        }

        return new static($result);
    }

    /**
     * Checks whether collection contains a specific value
     *
     * @param mixed $value
     * @return bool
     */
    public function contains($value)
    {
        if (!is_callable($value)) {
            return in_array($value, $this->items);
        } else {
            foreach ($this->items as $key => $item) {
                if ($value($key, $item)) {
                    return true;
                }
            }
            return false;
        }
    }

    /**
     * Sums the collection
     *
     * @param string $key Add this argument if you sum a multidimensional $arrayName = array('' => , );
     * @return int
     * @throws Exception
     */
    public function sum($key = '')
    {
        if ($key) {
            if (is_callable($key)) {
                return array_sum(array_map($key, $this->items));
            } else {
                return array_sum($this->items[$key]);
            }
        } else {
            return array_sum($this->items);
        }
    }

    /**
     * Cheks whether collection item exists by key
     * @param mixed $key
     * @return bool
     */
    public function exists($key)
    {
        return isset($this->items[$key]);
    }

    /**
     * Returns array keys
     */
    public function keys()
    {
        return array_keys($this->items);
    }

    /**
     * Serializes the collection
     * @return string
     */
    public function serialize()
    {
        return serialize($this->items);
    }

    /**
     * Converts collection to json
     * @param int|string $options
     * @param integer $depth
     * @return string
     */
    public function toJson($options = 0, $depth = 512)
    {
        return json_encode($this->items, $options, $depth);
    }

    /**
     * Applies sort on collection
     */
    public function sort()
    {
        sort($this->items);
        return $this;
    }

    /**
     * Applies ksort on collection
     */
    public function ksort()
    {
        ksort($this->items);
        return $this;
    }

    /**
     * Separates collection by key
     * @param string $key
     * @return Collection
     */
    public function separate($key)
    {
        $result = [];
        foreach ($this->items as $item) {
            if (!isset($result[$item[$key]])) {
                $result[$item[$key]] = new static;
            }
            $result[$item[$key]]->push($item);
        }

        return new static($result);
    }

    /**
     * Accumulates the collection into string or integer
     * @param callback $func
     * @param string|int $initial
     * @return mixed
     */
    public function reduce($func, $initial)
    {
        $accumulator = $initial;

        foreach ($this->items as $key => $item) {
            $accumulator = $func($accumulator, $item, $key);
        }

        return $accumulator;
    }

    /**
     * Implode the collection by delimiter
     * @param string $delimiter
     * @return string
     */
    public function implode($delimiter)
    {
        return implode($delimiter, $this->items);
    }

    /**
     * Reverse the order of collection items
     *
     * @param bool $preserve_keys
     * @return static
     */
    public function reverse($preserve_keys = true)
    {
        return new static(array_reverse($this->items, $preserve_keys));
    }

    /**
     * Returns the collection values
     *
     * @return static
     */
    public function values()
    {
        return new static(array_values($this->items));
    }

    /**
     * Returns a collection of the items specific key
     *
     * @param mixed $key
     * @return Collection
     */
    public function pluck($key)
    {
        return $this->map(function ($item) use($key) {
            if (is_object($item)) {
                return $item->{$key};
            }
            return $item[$key];
        });
    }

    /**
     * Applies array_map on collection
     *
     * @param Closure $func
     * @return static
     */
    public function map(Closure $func)
    {
        $result = [];

        foreach ($this->items as $key => $item) {
            $result[] = $func($item, $key);
        }

        return new self($result);
    }

    /**
     * @param array $array
     * @return static
     */
    public function zip(array $array)
    {
        $result = [];
        $collection = new static($array);
        foreach ($this->items as $key => $item) {
            $result[] = [$item, $collection->get($key)];
        }
        return new static($result);
    }

    /**
     * Get collection item by key
     *
     * @param $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (isset($this->items[$key])) {
            return $this->items[$key];
        }
        return $default;
    }

    /**
     * @param Collection|array $toMerge
     * @return static
     */
    public function merge($toMerge)
    {
        $array = $toMerge instanceof Collection ? $toMerge->all() : $toMerge;

        return new static(array_merge($this->items, $array));
    }

    /**
     * Returns the items of collection class
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Shuffles array
     * @return static
     */
    public function shuffle()
    {
        $items = $this->items;
        shuffle($items);

        return new static($items);
    }

    /**
     * Dumps the collection
     * @return void
     */
    public function dump()
    {
        d($this->items);
    }

    public function replace($value, $replaceTo)
    {
        $key = $this->find($value);
        $this->items[$key] = $replaceTo;
        return new static($this->items);
    }

    /**
     * Search for an element by its value
     *
     * @param mixed $val
     * @param bool $returnKey Return the key or the item itself
     * @param null $default
     * @return false|int|mixed|string
     */
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

    /**
     * {@inheritDoc}
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->items);
    }

    /**
     * {@inheritDoc}
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * {@inheritDoc}
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * {@inheritDoc}
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    /**
     * {@inheritDoc}
     * @see Countable::count()
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * {@inheritDoc}
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * @return mixed|null
     */
    public function loop()
    {
        $item = current($this->items);

        next($this->items);

        return $item;
    }

    /**
     * @return mixed
     */
    public function reset()
    {
        return reset($this->items);
    }

    /**
     * @return int|null|string
     */
    public function key()
    {
        return key($this->items);
    }

    /**
     * @return mixed
     */
    public function next()
    {
        return next($this->items);
    }

    /**
     * @return mixed
     */
    public function prev()
    {
        return prev($this->items);
    }

    /**
     * @return false|string
     */
    public function __toString()
    {
        return json_encode($this->items, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
    }

    public function toArray()
    {
        return $this->items;
    }

    public function only(...$keys)
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

    public function isEmpty()
    {
        return empty($this->items);
    }
    
    /**
     * 
     * @param $keys
     * @return static
     */
    public function except(...$keys)
    {
        return $this->filter(function($item, $key) use($keys) {
            return !in_array($key, $keys);
        });
    }

    public function make($abstraction = null)
    {
        return $this->map(function($item) use($abstraction) {
            if (!$abstraction) {
                return app()->make($item);
            }

            return app()->make($abstraction, $item);
        });
    }
}
