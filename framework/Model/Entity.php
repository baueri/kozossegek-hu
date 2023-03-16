<?php

namespace Framework\Model;

use Framework\Support\Arr;
use Framework\Support\StringHelper;

/**
 * @property null|string $id
 */
abstract class Entity
{
    /**
     * @param string|null|class-string<EntityQueryBuilder>
     * @phpstan-param class-string<EntityQueryBuilder>
     */
    protected static ?string $queryBuilder = null;

    protected static string $primaryCol = 'id';

    public static function updatedCol(): ?string
    {
        return null;
    }

    public readonly array $originalAttributes;

    public array $relations = [];

    public array $relations_count = [];

    public function __construct(protected ?array $attributes = [])
    {
        $this->originalAttributes = Arr::wrap($attributes);
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
        $relation = substr($name, 0, strrpos($name, '_count'));

        if (isset($this->relations_count[$relation])) {
            return $this->relations_count[$relation];
        }

        if (StringHelper::endsWith($name, '_count') && isset($this->relations[$relation])) {
            return $this->relations_count[$relation] = count($this->relations[$relation]);
        }

        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        return $this->relations[$name] ?? null;
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function fill(array $values): self
    {
        $this->attributes = array_merge($this->attributes, $values);
        return $this;
    }

    public function getAttributes($only = null): array
    {
        if (!$only) {
            return $this->attributes;
        }

        return Arr::only($this->attributes, $only);
    }

    public function only($only): array
    {
        return $this->getAttributes($only);
    }

    public function hasChanges(): bool
    {
        return !empty($this->getChanges());
    }

    public function getChanges(): array
    {
        $original = $this->originalAttributes;
        $newValues = $this->attributes;

        return array_diff($newValues, $original);
    }

    public function setRelation(string $name, $relation): void
    {
        $this->relations[$name] = $relation;
    }

    public static function query(): EntityQueryBuilder
    {
        if ($builder = static::$queryBuilder) {
            return $builder::query();
        }

        $model = get_class_name(static::class);

        $plural = StringHelper::plural($model);

        /** @var EntityQueryBuilder $class */
        $class = "\\App\\QueryBuilders\\{$plural}";
        return $class::query();
    }
}
