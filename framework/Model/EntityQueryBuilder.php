<?php

namespace Framework\Model;

use App\Models\ChurchGroupView;
use Closure;
use Framework\Database\Builder;
use Framework\Database\Repository\Events\ModelCreated;
use Framework\Database\Repository\Events\ModelDeleted;
use Framework\Event\EventDisptatcher;
use Framework\Model\Relation\HasRelations;
use Framework\Support\StringHelper;

/**
 * @phpstan-template T of \Framework\Model\Entity
 */
abstract class EntityQueryBuilder
{
    use HasRelations;

    public const TABLE = null;

    protected Builder $builder;

    final public function __construct()
    {
        $this->builder = builder(static::getTableName());
    }

    public static function getTableName(): ?string
    {
        if (static::TABLE) {
            return static::TABLE;
        }

        $plural = StringHelper::plural(
            get_class_name(static::getModelClass())
        );

        return StringHelper::snake($plural);
    }

    /**
     * @phpstan-return class-string<T>
     */
    abstract protected static function getModelClass(): string;

    /**
     * @return static
     */
    public function query(): EntityQueryBuilder
    {
        return new static();
    }

    public function count(): int
    {
        return $this->builder->count();
    }

    /**
     * @return Entity[]|ModelCollection
     * @phpstan-return T[]|\Framework\Model\ModelCollection
     */
    public function get()
    {
        return $this->loadRelations(
            new ModelCollection(array_map(function ($row) {
                return $this->getInstance($row);
            }, $this->builder->get()))
        );
    }

    /**
     * @param array|null $values
     * @return T|null
     */
    public function getInstance(?array $values = null)
    {
        if (!$values) {
            return null;
        }

        $class = static::getModelClass();

        return new $class($values);
    }

    /**
     * @param mixed $id
     * @return \Framework\Model\Entity|null
     * @phpstan-return \Framework\Model\Entity<T>|T|null
     */
    public function find($id): ?Entity
    {
        return $this->where(static::primaryCol(), $id)->first();
    }

    /**
     * @throws \Framework\Model\ModelNotFoundException
     * @phpstan-return T
     */
    public function findOrFail($id): Entity
    {
        return $this->getOrFail($this->find($id));
    }

    /**
     * @throws ModelNotFoundException
     */
    public function firstOrFail(): Entity
    {
        return $this->getOrFail($this->first());
    }

    /**
     * @throws ModelNotFoundException
     * @phpstan-return T
     */
    public function getOrFail(?Entity $model): Entity
    {
        if (!$model) {
            throw new ModelNotFoundException();
        }

        return $model;
    }

    /**
     * @phpstan-return \Framework\Model\Entity<T>|T|null
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

    public function orderBy($columns, ?string $order = null): EntityQueryBuilder
    {
        $this->builder->orderBy($columns, $order);

        return $this;
    }

    public function select($select = '*', $bindings = []): EntityQueryBuilder
    {
        $this->builder->select($select, $bindings);
        return $this;
    }

    public function distinct(): EntityQueryBuilder
    {
        $this->builder->distinct();

        return $this;
    }

    public function addSelect($select, array $bindings = []): EntityQueryBuilder
    {
        $this->builder->addSelect($select, $bindings);

        return $this;
    }

    /**
     * @param mixed $column
     * @param null $operator
     * @param null $value
     * @param string $clause
     * @return static
     */
    public function where($column, $operator = null, $value = null, string $clause = 'and'): EntityQueryBuilder
    {
        $this->builder->where($column, $operator, $value, $clause);

        return $this;
    }

    public function orWhere($column, $operator = null, $value = null): EntityQueryBuilder
    {
        return $this->where($column, $operator, $value, 'or');
    }

    public function whereIn($column, array $values, $clause = 'and'): EntityQueryBuilder
    {
        $this->builder->whereIn($column, $values, $clause);

        return $this;
    }

    public function whereNull($column): EntityQueryBuilder
    {
        $this->builder->whereNull($column);
        return $this;
    }

    public function whereNotNull($column): EntityQueryBuilder
    {
        $this->builder->whereNotNull($column);
        return $this;
    }

    public function whereRaw($where, $bindings = [], $clause = 'and'): EntityQueryBuilder
    {
        $this->builder->whereRaw($where, $bindings, $clause);

        return $this;
    }

    public function orWhereRaw($where, $bindings = []): EntityQueryBuilder
    {
        $this->builder->whereRaw($where, $bindings, 'or');
        return $this;
    }

    /**
     * @param string $table
     * @param Closure $callback
     * @param string $clause
     * @return static
     */
    public function whereExists(string $table, Closure $callback, string $clause = 'and'): EntityQueryBuilder
    {
        $this->builder->whereExists($table, $callback, $clause);
        return $this;
    }

    public function orWhereExists(string $table, $callback): EntityQueryBuilder
    {
        $this->builder->orWhereExists($table, $callback);
        return $this;
    }

    public function groupBy($gropBy): EntityQueryBuilder
    {
        $this->builder->groupBy($gropBy);

        return $this;
    }

    public function join(string $table, string $on, string $joinMode = '')
    {
        $this->builder->join($table, $on, $joinMode);

        return $this;
    }

    public function leftJoin(string $table, string $on): EntityQueryBuilder
    {
        return $this->join($table, $on, 'left');
    }

    public function joinRaw(string $join): EntityQueryBuilder
    {
        $this->builder->joinRaw($join);

        return $this;
    }

    public function update(array $values): int
    {
        return $this->builder->update($values);
    }

    public function save(Entity $entity, array $values): int
    {
        return $this->query()->where(static::primaryCol(), $entity->getId())->update($values);
    }

    public function insert(array $values)
    {
        return $this->builder->insert($values);
    }

    public function updateOrInsert(array $where, array $values = [])
    {
        return $this->builder->updateOrInsert($where, $values);
    }

    public function delete($model, bool $hardDelete = false)
    {
        if (is_numeric($model)) {
            $model = $this->find($model);
        }

        if (property_exists($model, 'deleted_at') && !$hardDelete) {
            return $this->save($model, ['deleted_at' => date('Y-m-d H:i:s')]);
        }

        $deleted = $this->query()->builder->where(static::primaryCol(), $model->getId())->delete();

        EventDisptatcher::dispatch(new ModelDeleted($model));

        return (bool) $deleted;
    }

    public function paginate(?int $perpage = null, ?int $page = null): PaginatedModelCollection
    {
        $rows = $this->builder->paginate($perpage, $page);
        $models = new PaginatedModelCollection(array_map(function ($row) {
            return $this->getInstance($row);
        }, $rows->rows()), $rows->perpage(), $rows->page(), $rows->total());
        return $this->loadRelations($models);
    }

    public function exists(): bool
    {
        return $this->builder->exists();
    }

    public function toSql(bool $withBindings = false): string
    {
        return $this->builder->toSql($withBindings);
    }

    public function macro($macroName, $callback): EntityQueryBuilder
    {
        $this->builder->macro($macroName, $callback);

        return $this;
    }

    public function apply($macro, array $args = []): EntityQueryBuilder
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

    public function getTable(): string
    {
        return $this->builder->getTable();
    }

    public static function primaryCol(): string
    {
        /* @var $model Entity */
        $model = static::getModelClass();

        return $model::getPrimaryCol();
    }

    public function dd()
    {
        dd($this->toSql(true));
    }
}
