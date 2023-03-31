<?php

declare(strict_types=1);

namespace Framework\Model;

use Error;
use Framework\Model\Relation\Relation;
use Framework\Support\Arr;
use ReflectionMethod;

/**
 * @property null|string $id
 */
abstract class Entity
{
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
        $relation = null;
        if (str_ends_with($name, '_count')) {
            $relation = substr($name, 0, strrpos($name, '_count'));
            if (isset($this->relations_count[$relation])) {
                return $this->relations_count[$relation];
            }

            if (isset($this->relations[$relation])) {
                return $this->relations_count[$relation] = count($this->relations[$relation]);
            }
        }

        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        if (isset($this->relations[$name])) {
            return $this->relations[$name] ?? null;
        }

        if (is_numeric($relation) && $this->builder && method_exists($this->builder, $relation)) {
            $method = new ReflectionMethod($this->builder, $relation);
            $returnType = $method->getReturnType();
            if ($returnType->getName() === Relation::class) {
                /** @var \Framework\Model\EntityQueryBuilder $builder */
                $builder = new $this->builder;
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
        if ($this->builder && method_exists($this->builder, $name)) {
            return (new $this->builder)->{$name}()->buildQuery($this);
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
}
