<?php

namespace Framework\Model;

use Framework\Support\StringHelper;

/**
 * @property null|string $id
 */
abstract class Entity
{
    protected static string $primaryCol = 'id';

    protected array $originalAttributes = [];

    public array $relations = [];

    protected array $relations_count = [];

    public function __construct(protected array $attributes = [])
    {
        $this->originalAttributes = $attributes;
    }

    /**
     * @param array $attributes
     * @return static
     * @phpstan-return static
     */
    public static function make(array $attributes = []): self
    {
        return new static($attributes);
    }

    public function exists(): bool
    {
        return (bool) $this->getId();
    }

    public function getId()
    {
        return $this->{static::$primaryCol};
    }

    public static function getPrimaryCol(): string
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

    public function isDeleted(): bool
    {
        return (bool) $this->deleted_at;
    }
}
