<?php

namespace Framework\Model;

abstract class Model
{
    public $id;

    protected static string $primaryCol = 'id';

    protected array $originalAttributes = [];

    final public function __construct(?array $attributes = [])
    {
        if (!$attributes) {
            return;
        }

        $this->setAttributes($attributes);

        $this->originalAttributes = $attributes;
    }

    public static function make(array $attributes = []): self
    {
        return new static($attributes);
    }

    public static function getPrimaryCol(): string
    {
        return static::$primaryCol;
    }

    public function getId()
    {
        return $this->{static::$primaryCol};
    }

    public function exists(): bool
    {
        return (bool) $this->getId();
    }

    public function setId($id)
    {
        $this->{static::$primaryCol} = $id;
    }

    public function getOriginalAttributes(): array
    {
        return $this->originalAttributes;
    }

    public function is($model): bool
    {
        return $model instanceof $this && $this->getId() == $model->getId();
    }

    public function isDeleted(): bool
    {
        return property_exists($this, 'deleted_at') && $this->deleted_at;
    }

    public function update(array $data)
    {
        $this->setAttributes($data);
    }

    protected function setAttributes(array $values): Model
    {
        foreach ($values as $col => $value) {
            if (property_exists($this, $col)) {
                $this->{$col} = $value;
            }
        }

        return $this;
    }

    /**
     * @param string[] $only
     * @return array
     */
    public function valuesToArray(array $only = []): array
    {
        $only = (array) $only;

        $values = [];

        $attributesToGet = array_keys($this->getOriginalAttributes());
        if ($only) {
            $attributesToGet = array_filter(
                (array)$attributesToGet,
                fn ($attribute) => in_array($attribute, $only)
            );
        }

        foreach ($attributesToGet as $column) {
            if (property_exists($this, $column)) {
                $values[$column] = $this->{$column};
            }
        }

        return $values;
    }

    public function getChanges(): array
    {
        $original = $this->getOriginalAttributes();
        $newValues = $this->valuesToArray();

        $changes = [];
        foreach ($newValues as $key => $value) {
            if ($value != $original[$key]) {
                $changes[$key] = $value;
            }
        }
        return $changes;
    }
}
