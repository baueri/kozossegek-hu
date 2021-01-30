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
    protected array $originalValues = [];

    /**
     * Model constructor.
     * @param array $values
     */
    public function __construct($values = [])
    {
        $this->setProperties($values);

        $this->originalValues = $values;
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

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->{static::$primaryCol} = $id;
    }

    /**
     * @return array|mixed[]
     */
    public function getOriginalValues()
    {
        return $this->originalValues;
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
        $this->setProperties($data);
    }

    /**
     *
     * @param array $values
     * @return static
     */
    protected function setProperties(array $values)
    {
        foreach ($values as $col => $value) {
            if (property_exists($this, $col)) {
                $this->{$col} = $value;
            }
        }

        return $this;
    }

    /**
     * @return array
     */
    public function valuesToArray()
    {
        $values = [];

        foreach (array_keys($this->getOriginalValues()) as $column) {
            if (property_exists($this, $column)) {
                $values[$column] = $this->{$column};
            }
        }

        return $values;
    }

    public function getChanges()
    {
        $original = $this->getOriginalValues();
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
