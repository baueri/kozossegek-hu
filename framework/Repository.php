<?php


namespace Framework;

use Framework\Database\PaginatedResultSet;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Model\ModelCollection;
use Framework\Model\Model;
use Framework\Model\PaginatedModelCollection;
use Framework\Model\ModelNotFoundException;
use Framework\Traits\Makeable;

abstract class Repository
{
    use Makeable;

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

    public function findOrFail($id)
    {
        if ($model = $this->find($id)) {
            return $model;
        }
       
        throw new ModelNotFoundException();
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
        return $this->getInstances($this->getBuilder()->get());
    }

    /**
     * @param array|PaginatedResultSetInterface $rows
     * @return ModelCollection|Model[]|PaginatedResultSet
     */
    public function getInstances($rows)
    {
        if ($rows instanceof PaginatedResultSetInterface) {
            return new PaginatedModelCollection(array_map(function($row){
                return $this->getInstance($row);
            }, $rows->rows()), $rows->perpage(), $rows->page(), $rows->total());
        }

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
        
        foreach (array_keys($model->getOriginalValues()) as $column) {
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
        if (property_exists($model, 'deleted_at')) {
            $model->deleted_at = date('Y-m-d H:i:s');
            return $this->save($model);
        }
        
        return $this->getBuilder()->where(static::getPrimaryCol(), $model->getId())->delete();
        
    }
    
    public function updateOrCreate(array $where, array $data)
    {
        return $this->getBuilder()->updateOrInsert($where, $data);
    }

}