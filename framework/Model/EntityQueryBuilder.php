<?php

namespace Framework\Model;

use Framework\Database\Builder;
use Framework\Database\PaginatedResultSet;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Database\Repository\Events\ModelCreated;
use Framework\Event\EventDisptatcher;
use Framework\Model\Relation\HasRelations;
use Framework\Support\Collection;
use Framework\Support\StringHelper;

/**
 * @package Framework\Model
 * @psalm-template T of \Framework\Model\Entity
 */
abstract class EntityQueryBuilder
{
    use HasRelations;

    public const TABLE = null;

    protected Builder $builder;

    protected array $preparedRelations = [];

    public function __construct()
    {
        $this->builder = builder(static::getTableName());
    }

    public static function getTableName(): ?string
    {
        if (self::TABLE) {
            return self::TABLE;
        }

        $plural = StringHelper::plural(
            get_class_name(static::getModelClass())
        );

        return StringHelper::snake($plural);
    }

    /**
     * @return string
     * @psalm-return class-string<T>
     */
    abstract protected static function getModelClass(): string;

    /**
     * @return static
     */
    public static function init()
    {
        return new static();
    }

    public function count()
    {
        return $this->builder->count();
    }

    /**
     * @return Entity[]|ModelCollection|PaginatedModelCollection|T[]|PaginatedResultSet|Collection
     */
    public function get()
    {
        $rows = $this->builder->get();
        return $this->getInstances($rows);
    }

    /**
     * @param array|PaginatedResultSetInterface $rows
     * @return Collection|Entity[]|PaginatedResultSet
     * @psalm-return T[]|Collection|PaginatedResultSet
     */
    public function getInstances($rows)
    {
        if ($rows instanceof PaginatedResultSetInterface) {
            $models = new PaginatedModelCollection(array_map(function ($row) {
                return $this->getInstance($row);
            }, $rows->rows()), $rows->perpage(), $rows->page(), $rows->total());
        } else {
            $models = new ModelCollection(array_map(function ($row) {
                return $this->getInstance($row);
            }, $rows));
        }

        $this->loadRelations($models);

        return $models;
    }

    /**
     * @param array|null $values
     * @return Entity|mixed
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

    public function find($id): ?Entity
    {
        return $this->where(static::primaryCol(), $id)->first();
    }

    public function firstOrFail()
    {
        return $this->getOrFail($this->first());
    }

    public function getOrFail(?Model $model)
    {
        if (!$model) {
            throw new ModelNotFoundException();
        }

        return $model;
    }

    /**
     * @psalm-return T|null
     * @return \Framework\Model\Entity|mixed|null|T
     */
    public function first(): ?Entity
    {
        return $this->getInstance($this->builder->first());
    }

    public function limit($limit)
    {
        $this->builder->limit($limit);

        return $this;
    }

    public function orderBy($columns, ?string $order = null)
    {
        $this->builder->orderBy($columns, $order);

        return $this;
    }

    public function select($select = '*', $bindings = [])
    {
        $this->builder->select($select, $bindings);
        
        return $this;
    }

    public function distinct()
    {
        $this->builder->distinct();

        return $this;
    }

    public function addSelect($select, array $bindings = [])
    {
        $this->builder->addSelect($select, $bindings);

        return $this;
    }

    public function where($column, $operator = null, $value = null, $clause = 'and')
    {
        $this->builder->where($column, $operator, $value, $clause);

        return $this;
    }

    public function whereIn($column, array $values, $clause = 'and')
    {
        $this->builder->whereIn($column, $values, $clause);

        return $this;
    }

    public function whereRaw($where, $bindings = [], $clause = 'and')
    {
        $this->builder->whereRaw($where, $bindings, $clause);

        return $this;
    }

    public function groupBy($gropBy)
    {
        $this->builder->groupBy($gropBy);

        return $this;
    }

    public function join(string $table, string $on, string $joinMode = '')
    {
        $this->builder->join($table, $on, $joinMode);

        return $this;
    }

    public function leftJoin(string $table, string $on)
    {
        return $this->join($table, $on, 'left');
    }

    public function joinRaw(string $join)
    {
        $this->builder->joinRaw($join);

        return $this;
    }

    public function update(array $values)
    {
        return $this->builder->update($values);
    }

    public function insert(array $values)
    {
        return $this->builder->insert($values);
    }

    public function updateOrInsert(array $where, array $values = [])
    {
        return $this->builder->updateOrInsert($where, $values);
    }

    public function delete()
    {
        return $this->builder->delete();
    }

    public function paginate(?int $perpage = null, ?int $page = null)
    {
        return $this->getInstances($this->builder->paginate($perpage, $page));
    }

    public function exists(): bool
    {
        return $this->builder->exists();
    }

    public function toSql(bool $withBindings = false)
    {
        return $this->builder->toSql($withBindings);
    }

    public function macro($macroName, $callback)
    {
        $this->builder->macro($macroName, $callback);

        return $this;
    }

    public function apply($macro, $args)
    {
        $this->builder->apply($macro, $args);

        return $this;
    }

    public function create(array $values)
    {
        $values['id'] = $this->insert($values);

        $model = $this->getInstance($values);

        EventDisptatcher::dispatch(new ModelCreated($model));

        return $model;
    }

    public static function primaryCol(): string
    {
        /* @var $model Entity */
        $model = static::getModelClass();

        return $model::getPrimaryCol();
    }
}
