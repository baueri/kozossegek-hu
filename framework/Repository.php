<?php

namespace Framework;

use Framework\Database\Builder;
use Framework\Database\PaginatedResultSet;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Model\Model;
use Framework\Model\ModelCollection;
use Framework\Model\ModelNotFoundException;
use Framework\Model\ModelRepositoryBuilder;
use Framework\Model\PaginatedModelCollection;
use Framework\Support\Collection;
use Framework\Support\Arr;

/**
 * Class Repository
 * @package Framework
 * @psalm-template T of \Framework\Model\Model
 */
abstract class Repository
{
    /**
     * @return Database\Builder
     */
    public function getBuilder(): Builder
    {
        return builder()->select('*')->from(static::getTable());
    }

    /**
     * @param string|int $id
     * @return Model|null
     * @psalm-return T|null
     */
    public function find($id): ?Model
    {
        $row = $this->getBuilder()->where(static::getPrimaryCol(), $id)->first();
        return $this->getInstance($row);
    }

    /**
     * @param string|int $id
     * @return Model
     * @psalm-return T|null
     * @throws ModelNotFoundException
     */
    public function findOrFail($id): Model
    {
        return $this->getOrFail($this->find($id));
    }

    public static function getPrimaryCol()
    {
        $class = static::getModelClass();
        return $class::getPrimaryCol();
    }

    /**
     * @return string|Model
     * @psalm-return class-string<T>
     */
    abstract protected static function getModelClass(): string;

    /**
     * @param array|null $values
     * @return Model|mixed
     * @psalm-return T|null
     */
    public function getInstance(?array $values = null)
    {
        if (!$values) {
            return null;
        }

        $class = static::getModelClass();

        return new $class($values);
    }

    public function getOrFail(?Model $model)
    {
        if (!$model) {
            throw new ModelNotFoundException();
        }

        return $model;
    }

    /**
     * @return ModelCollection|Model[]
     * @psalm-return T[]
     */
    public function all()
    {
        return $this->getInstances($this->getBuilder()->get());
    }

    /**
     * @param array|PaginatedResultSetInterface $rows
     * @return ModelCollection|Model[]|PaginatedResultSet
     * @psalm-return T[]|ModelCollection|PaginatedResultSet
     */
    public function getInstances($rows)
    {
        if ($rows instanceof PaginatedResultSetInterface) {
            return new PaginatedModelCollection(array_map(function ($row) {
                return $this->getInstance($row);
            }, $rows->rows()), $rows->perpage(), $rows->page(), $rows->total());
        }

        return new ModelCollection(array_map(function ($row) {
            return $this->getInstance($row);
        }, $rows));
    }

    /**
     * @param array $values
     * @return Model|mixed|T
     * @psalm-return T
     * @phpstan-return T
     */
    public function create(array $values)
    {
        $values['id'] = $this->insert($values);

        $model = $this->getInstance($values);

        Event\EventDisptatcher::dispatch(new Database\Repository\Events\ModelCreated($model));

        return $model;
    }

    /**
     * @param array $values
     * @return int
     */
    public function insert(array $values)
    {
        return  $this->getBuilder()->insert($values);
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

        $id = $this->insert(array_filter($model->valuesToArray()));
        $model->setId($id);
        return (bool) $id;
    }

    /**
     * @param Model $model
     * @param array $data
     * @psalm-param T
     * @return bool
     */
    public function update(Model $model, $data = [])
    {
        if ($data) {
            $model->update($data);
        }

        $changes = $model->getChanges();

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

        $updated = (bool) db()->update($query, ...array_merge($values, [$id]));

        Event\EventDisptatcher::dispatch(new Database\Repository\Events\ModelUpdated($model));

        return $updated;
    }

    abstract public static function getTable(): string;

    /**
     * @param Model|int $model
     * @param bool $hardDelete
     * @return bool
     */
    public function delete($model, bool $hardDelete = false)
    {
        if (property_exists($model, 'deleted_at') && !$hardDelete) {
            $model->deleted_at = date('Y-m-d H:i:s');
            return $this->save($model);
        }

        $deleted = $this->getBuilder()->where(static::getPrimaryCol(), $model->getId())->delete();

        Event\EventDisptatcher::dispatch(new Database\Repository\Events\ModelDeleted($model));

        return $deleted;
    }

    /**
     * @param $id
     * @param false $forceDelete
     * @return bool
     * @throws ModelNotFoundException
     */
    public function deleteById($id, $forceDelete = false): bool
    {
        return $this->delete($this->findOrFail($id), $forceDelete);
    }

    public function forceDelete($model)
    {
        return $this->delete($model, true);
    }

    /**
     * @param Model[]|Collection $models
     * @param bool $forceDelete
     */
    public function deleteMultiple($models, $forceDelete = false)
    {
        Arr::each($models, fn ($model) => $this->delete($model, $forceDelete));
    }

    public function deleteMultipleByIds($ids, $forceDelete)
    {
        $this->deleteMultiple(
            $this->getInstances(
                $this->getBuilder()
                    ->whereIn(self::getPrimaryCol(), $ids)
                    ->get()
            ),
            $forceDelete
        );
    }

    public function query(): ModelRepositoryBuilder
    {
        return new ModelRepositoryBuilder($this);
    }
}
