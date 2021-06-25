<?php

namespace Framework\Model;

use Framework\Support\StringHelper;

abstract class Entity
{
    protected static string $primaryCol = 'id';

    protected array $attributes = [];

    protected array $originalAttributes = [];

    public array $relations = [];

    protected array $relations_count = [];

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;

        $this->originalAttributes = $attributes;
    }

    public function exists(): bool
    {
        return (bool) $this->getId();
    }

    public function getId()
    {
        return $this->{static::$primaryCol};
    }

    public function setId($id)
    {
        $this->{static::$primaryCol} = $id;
    }

    public static function getPrimaryCol()
    {
        return static::$primaryCol;
    }

    public function __get($name)
    {
        if (StringHelper::endsWith($name, '_count')) {
            $relation = substr($name, 0, strrpos($name, '_count'));
            return $this->relations_count[$name] ??= count($this->relations[$relation] ?? []);
        }

        return $this->attributes[$name] ?? $this->relations[$name] ?? null;
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
