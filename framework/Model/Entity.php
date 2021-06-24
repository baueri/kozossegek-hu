<?php

namespace Framework\Model;

abstract class Entity
{
    protected static string $primaryCol = 'id';

    protected array $attributes = [];

    protected array $originalAttributes = [];

    public array $relations = [];

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
