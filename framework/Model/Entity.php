<?php

declare(strict_types=1);

namespace Framework\Model;

use Error;
use Framework\Model\Relation\Relation;
use Framework\Support\Arr;
use Framework\Support\StringHelper;
use ReflectionMethod;
use RuntimeException;

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

    protected ?string $builder = null;

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
        if (str_ends_with($name, '_count')) {
            $relation = substr($name, 0, strrpos($name, '_count'));
            if (isset($this->relations_count[$relation])) {
                return $this->relations_count[$relation];
            }

            if (isset($this->relations[$relation])) {
                return $this->relations_count[$relation] = count($this->relations[$relation]);
            }
        } else {
            $relation = $name;
        }

        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        if (isset($this->relations[$relation])) {
            return $this->relations[$relation] ?? null;
        }

        if ($relation && method_exists($builder = static::query(), $relation)) {
            $method = new ReflectionMethod($builder, $relation);
            $returnType = $method->getReturnType();
            if ($returnType->getName() === Relation::class) {
                $queryRelation = $builder->getRelation($relation);
                $builder->fillRelations($this, $queryRelation, str_ends_with($name, '_count'));
                if (str_ends_with($name, '_count')) {
                    return $this->relations_count[$relation] ?? 0;
                } else {
                    return $this->relations[$relation] ?? null;
                }
            }
        }
        return null;
    }

    public function __call(string $name, array $arguments)
    {
        if (static::$queryBuilder && method_exists(static::$queryBuilder, $name)) {
            return (new static::$queryBuilder)->{$name}()->buildQuery($this);
        }

        throw new Error("Call to undefined method {$name}");
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

    public function setRelation(string $relation, $value): void
    {
        $this->relations[$relation] = $value;
    }

    public function getAttributes($only = null): array
    {
        if (!$only) {
            return $this->attributes;
        }

        return Arr::only($this->attributes, $only);
    }

    public function hasAttribute(string $column): bool
    {
        return array_key_exists($column, $this->originalAttributes);
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

    public static function query(): EntityQueryBuilder
    {
        if ($builder = static::$queryBuilder) {
            return $builder::query();
        }

        $model = get_class_name(static::class);

        $plural = StringHelper::plural($model);

        /** @var class-string<EntityQueryBuilder>|null $class */
        $class = "\\App\\QueryBuilders\\{$plural}";
        if (class_exists($class)) {
            return $class::query();
        }
        throw new RuntimeException('Could not guess query builder class');
    }
}
