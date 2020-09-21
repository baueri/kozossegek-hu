<?php


namespace Framework;

use Framework\Database\Repository\ModelCollection;
use Framework\Model\Model;
use Framework\Traits\Makeable;

abstract class Repository
{
    use Makeable;

    /**
     * @var string[]
     */
    protected static $dbColumns = [];

    /**
     * @return Database\Builder
     */
    public function getBuilder()
    {
        return builder()->select('*')->from(static::getTable());
    }

    /**
     * @param $id
     * @return Model
     */
    public function find($id)
    {
        $row = $this->getBuilder()->where(static::getPrimaryCol(), $id)->first();

        return $this->getInstance($row);
    }

    /**
     * @return Model|mixed
     */
    public function first()
    {
        $row = $this->getBuilder()->first();

        return $this->getInstance($row);
    }
    

    public static function getPrimaryCol()
    {
        $class = static::getModelClass();
        return $class::getPrimaryCol();
    }

    /**
     * @return string|Model
     */
    abstract public static function getModelClass(): string;

    /**
     * @param array $values
     * @return Model|mixed
     */
    public function getInstance($values = null)
    {
        if (!$values) {
            return null;
        }

        $class = static::getModelClass();

        return new $class($values);
    }

    /**
     * @return ModelCollection|Model[]
     */
    public function all()
    {
        $rows = $this->getInstances($this->getBuilder()->get());

        return $rows;
    }

    /**
     * @param array $rows
     * @return ModelCollection|Model[]|mixed[]
     */
    public function getInstances(array $rows)
    {
        return new ModelCollection(array_map(function ($row) {
            return $this->getInstance($row);
        }, $rows));
    }

    /**
     * @param array $values
     * @return Model|mixed
     */
    public function create(array $values)
    {
        $values['id'] = $this->insert($values);

        return $this->getInstance($values);
    }
    
    /**
     * @param array $values
     * @return int
     */
    public function insert(array $values)
    {
        return $this->getBuilder()->insert($values);
    }

    /**
     * @param Model $model
     * @return bool
     */
    public function save(Model $model)
    {
        if ($model->exists()) {
            return $this->update($model);
        }

        $id = $this->insert(array_filter($this->valuesToArray($model)));
        $model->setId($id);
        return (bool)$id;
    }

    /**
     * @param Model $model
     * @return bool
     */
    public function update(Model $model)
    {
        $changes = $this->getChanges($model);

        if (!$changes) {
            return false;
        }

        $id = $model->getId();
        $table = static::getTable();
        $values = array_values($changes);
        $dbColumns = array_keys($changes);
        $primaryCol = $model::getPrimaryCol();
        $set = implode(', ', array_map(function ($column) {
            return "$column=?";
        }, $dbColumns));

        $query = sprintf('UPDATE %s SET %s WHERE %s=?', $table, $set, $primaryCol);

        return (bool)db()->update($query, ...array_merge($values, [$id]));
    }

    public function getChanges(Model $model)
    {
        $original = $model->getOriginalValues();
        $newValues = $this->valuesToArray($model);
        return array_diff($newValues, $original);
    }

    /**
     * @param Model $model
     * @return array
     */
    public function valuesToArray(Model $model)
    {
        $values = [];
        foreach (static::$dbColumns as $column) {
            $values[$column] = $model->{$column};
        }

        return $values;
    }

    abstract public static function getTable(): string;

    /**
     * @param Model $model
     * @return bool
     */
    public function delete(Model $model)
    {
        $query = sprintf('DELETE FROM %s WHERE %s=?', static::getTable(), static::getPrimaryCol());
        db()->execute($query, $model->getId());

        return true;
    }

    public function updateOrCreate(array $where, array $data)
    {
        return $this->getBuilder()->updateOrInsert($where, $data);
    }

    public static function getDbColumns()
    {
        return static::$dbColumns;
    }

}