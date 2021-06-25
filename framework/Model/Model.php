<?php

namespace Framework\Model;

abstract class Model
{
    /**
     * @var mixed
     */
    public $id;

    /**
     * @var string
     */
    protected static string $primaryCol = 'id';

    /**
     * @var mixed[]
     */
    protected array $originalAttributes = [];

    public function __construct(array $attributes = [])
    {
        $this->setAttributes($attributes);

        $this->originalAttributes = $attributes;
    }

    /**
     * @param array $attributes
     * @return static
     */
    public static function make(array $attributes = [])
    {
        return new static($attributes);
    }

    /**
     * @return string
     */
    public static function getPrimaryCol()
    {
        return static::$primaryCol;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->{static::$primaryCol};
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return (bool) $this->getId();
    }

    public function setId($id)
    {
        $this->{static::$primaryCol} = $id;
    }

    /**
     * @return array
     */
    public function getOriginalAttributes()
    {
        return $this->originalAttributes;
    }

    /**
     * @param mixed $model
     * @return bool
     */
    public function is($model): bool
    {
        return $model instanceof $this && $this->getId() == $model->getId();
    }

    public function isDeleted(): bool
    {
        return property_exists($this, 'deleted_at') && (bool) $this->deleted_at;
    }

    /**
     *
     * @param array $data
     */
    public function update(array $data)
    {
        $this->setAttributes($data);
    }

    /**
     *
     * @param array $values
     * @return static
     */
    protected function setAttributes(array $values)
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

    public function getChanges()
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

    public function hasChanges(): bool
    {
        return !empty($this->getChanges());
    }
}
