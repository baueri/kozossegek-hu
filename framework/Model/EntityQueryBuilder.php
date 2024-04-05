<?php

declare(strict_types=1);

namespace Framework\Model;

use Cake\Utility\Inflector;
use Closure;
use Framework\Database\Builder;
use Framework\Database\Repository\Events\ModelCreated;
use Framework\Database\Repository\Events\ModelDeleted;
use Framework\Event\EventDisptatcher;
use Framework\Model\Exceptions\ModelNotFoundException;
use Framework\Model\Exceptions\QueryBuilderException;
use Framework\Model\Relation\HasRelations;
use Framework\Support\Collection;
use RuntimeException;

/**
 * @phpstan-template T of Entity
 */
class EntityQueryBuilder
{
    use HasRelations;

    public const TABLE = null;

    public readonly Builder $builder;

    public readonly ?string $modelClass;

    /**
     * @phpstan-param class-string<T>|null $model
     */
    final public function __construct(?string $model = null)
    {
        $this->modelClass = $model ?: null;
        $this->builder = builder($this->getTableName());
    }

    public function getTableName(): ?string
    {
        if (static::TABLE) {
            return static::TABLE;
        }

        if ($this->modelClass) {
            return Inflector::tableize(get_class_name($this->modelClass));
        }

        return Inflector::tableize(get_class_name(static::class));
    }

    /**
     * @phpstan-return class-string<T>
     */
    public function getModelClass(): string
    {
        if ($this->modelClass) {
            return $this->modelClass;
        }

        $className = Inflector::singularize(get_class_name(static::class));

        $modelClassName = "App\\Models\\{$className}";

        if (!class_exists($modelClassName)) {
            throw new RuntimeException("Could not instantiate {$modelClassName} from entity " . static::class);
        }

        return $modelClassName;
    }

    /**
     * @phpstan-param class-string<T>|null $model
     * @phpstan-return static<T>
     */
    public static function query(?string $model = null): static
    {
        return new static($model);
    }

    public function count(): int
    {
        return $this->builder->count();
    }

    public function countBy(string $column): Collection
    {
        return collect($this->builder->countBy($column));
    }

    public function pluck(string $key, ?string $keyBy = null): Collection
    {
        if (!$this->builder->getSelect()) {
            return collect($this->builder->pluck($key, $keyBy));
        }

        return $this->get()->pluck($key, $keyBy);
    }

    /**
     * @phpstan-return T[]|ModelCollection<T>
     */
    public function get(): ModelCollection
    {
        return $this->loadRelations(
            new ModelCollection(array_map(function ($row) {
                return $this->getInstance($row);
            }, $this->builder->get()))
        );
    }

    public function delete(bool $hardDelete = false): int
    {
        if (class_uses_trait($this, SoftDeletes::class) && !$hardDelete) {
            return $this->update(['deleted_at' => date('Y-m-d H:i:s')]);
        }

        return $this->builder->delete();
    }

    public function hardDelete(): int
    {
        return $this->delete(true);
    }

    /**
     * @return Entity|T|null
     */
    public function getInstance(?array $values = null)
    {
        if (!$values) {
            return null;
        }

        return $this->makeModel($values);
    }

    protected function makeModel($values = null)
    {
        $class = $this->getModelClass();

        return new $class($values);
    }

    /**
     * @phpstan-return Entity<T>|T|null
     */
    public function find(mixed $id): ?Entity
    {
        return $this->wherePK($id)->first();
    }

    /**
     * @param $id
     * @return Entity
     * @phpstan-return T
     */
    public function findOrNew($id): Entity
    {
        return $this->find($id) ?? $this->makeModel();
    }

    public function fetchFirst(?string $column = null)
    {
        return $this->builder->fetchFirst($column);
    }

    /**
     * @phpstan-return Entity<T>|T|null
     * @throws ModelNotFoundException
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
     * @phpstan-return Entity<T>|T|null
     */
    public function first(): ?Entity
    {
        return $this->loadRelations($this->getInstance($this->builder->first()));
    }

    public function limit($limit): EntityQueryBuilder
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

    public function where(callable|string $column, $operator = null, $value = null, string $clause = 'and'): static
    {
        $this->builder->where($column, $operator, $value, $clause);

        return $this;
    }

    public function orWhere($column, $operator = null, $value = null): static
    {
        return $this->where($column, $operator, $value, 'or');
    }

    public function whereIn($column, $values, $clause = 'and'): static
    {
        $this->builder->whereIn($column, $values, $clause);

        return $this;
    }

    public function whereNull($column, $clause = 'and'): EntityQueryBuilder
    {
        $this->builder->whereNull($column, $clause);
        return $this;
    }

    public function whereNotNull($column): static
    {
        $this->builder->whereNotNull($column);
        return $this;
    }

    public function whereRaw($where, $bindings = [], $clause = 'and'): static
    {
        $this->builder->whereRaw($where, $bindings, $clause);

        return $this;
    }

    public function wherePK($value): static
    {
        return $this->where($this->primaryCol(), $value);
    }

    public function orWhereRaw($where, $bindings = []): static
    {
        $this->builder->whereRaw($where, $bindings, 'or');
        return $this;
    }

    public function whereExists(Builder|EntityQueryBuilder $table, ?Closure $callback = null, string $clause = 'and'): static
    {
        $this->builder->whereExists($table instanceof EntityQueryBuilder ? $table->builder : $table, $callback, $clause);
        return $this;
    }

    /**
     * @phpstan-return static
     */
    public function whereDoesnExist(Builder|EntityQueryBuilder $table, ?Closure $callback = null, string $clause = 'and'): static
    {
        $this->builder->whereDoesnExist($table instanceof EntityQueryBuilder ? $table->builder : $table, $callback, $clause);
        return $this;
    }

    public function wherePast($column, $clause = 'and'): self
    {
        $this->builder->wherePast($column, $clause);
        return $this;
    }

    public function groupBy($gropBy): EntityQueryBuilder
    {
        $this->builder->groupBy($gropBy);

        return $this;
    }

    public function having(string $having, array $bindings = []): static
    {
        $this->builder->having($having, $bindings);
        return $this;
    }

    public function when($expression, Closure $callback): static
    {
        if ($expression) {
            $callback($this, $expression);
        }

        return $this;
    }

    public function join(string $table, string $on, string $joinMode = ''): static
    {
        $this->builder->join($table, $on, $joinMode);

        return $this;
    }

    public function leftJoin(string $table, string $on): static
    {
        return $this->join($table, $on, 'left');
    }

    public function joinRaw(string $join): static
    {
        $this->builder->joinRaw($join);

        return $this;
    }

    public function update(array $values): int
    {
        return $this->builder->update($values);
    }

    /**
     * @throws QueryBuilderException
     */
    public function touch(Entity $entity): int
    {
        if (!$entity::updatedCol()) {
            throw new QueryBuilderException('method updatedCol must return a valid column name.');
        }

        return static::query()->where($this->primaryCol(), $entity->getId())
            ->update([$entity::updatedCol() => now()->format('Y-m-d H:i:s')]);
    }

    public function save(Entity $entity, ?array $values = null): int
    {
        $toUpdate = $values ?: $entity->getAttributes();
        if ($values) {
            $entity->fill($values);
        }

        $col = $entity::updatedCol();
        if ($col && !array_key_exists($col, $toUpdate)) {
            $toUpdate[$col] = $entity->{$col} = now();
        }

        return $this->query()->where($this->primaryCol(), $entity->getId())->update($toUpdate);
    }

    public function insert(array $values): int|string
    {
        return $this->builder->insert($values);
    }

    public function updateOrInsert(array $where, array $values = []): int|string
    {
        return $this->builder->updateOrInsert($where, $values);
    }

    public function deleteModel(int|string|Entity $model, bool $hardDelete = false): bool
    {
        if (is_numeric($model)) {
            $model = $this->find($model);
        }
        

        if (class_uses_trait($model, SoftDeletes::class) && !$hardDelete) {
            return (bool) $this->save($model, ['deleted_at' => date('Y-m-d H:i:s')]);
        }

        $deleted = $this->query()->builder->where($this->primaryCol(), $model->getId())->delete();

        EventDisptatcher::dispatch(new ModelDeleted($model));

        return (bool) $deleted;
    }

    public function hardDeleteModel($model): bool
    {
        return $this->deleteModel($model, true);
    }

    public function paginate(?int $perpage = null, ?int $page = null): PaginatedModelCollection
    {
        $rows = $this->builder->paginateRaw($perpage, $page);
        $models = new PaginatedModelCollection(array_map(function ($row) {
            return $this->getInstance($row);
        }, $rows['rows']), $rows['limit'], $rows['page'], $rows['total']);
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

    public function macro($macroName, $callback): static
    {
        $this->builder->macro($macroName, $callback);

        return $this;
    }

    public function apply($macro, ...$args): static
    {
        $this->builder->apply($macro, ...$args);

        return $this;
    }

    public static function truncate(): void
    {
        static::query()->builder->truncate();
    }

    /**
     * @param array|Entity $values
     * @phpstan-param T|array $values
     * @return Entity|null
     * @phpstan-return T
     */
    public function create(array|Entity $values): Entity|null
    {
        $model = $values instanceof Entity ? $values : null;
        $toSave = $model ? $model->getAttributes() : $values;

        $insertId = $this->insert($toSave);

        if (isset($model)) {
            $model->{$this->primaryCol()} = $insertId;
        } else {
            $model = $this->getInstance($toSave + [$this->primaryCol() => $insertId]);
        }

        EventDisptatcher::dispatch(new ModelCreated($model));

        return $model;
    }

    public function getTable(): string
    {
        return $this->builder->getTable();
    }

    public function getSelect(): array
    {
        return $this->builder->getSelect();
    }

    public function getWhere(): array
    {
        return $this->builder->getWhere();
    }

    public function each(Closure $callback, int $chunks = 1000): void
    {
        $limit = 0;
        $builder = clone $this;
        $builder->limit("0, {$chunks}");

        while (($rows = $builder->get())->isNotEmpty()) {
            $offset = (++$limit) * $chunks;
            $builder->limit("{$offset}, {$chunks}");
            $rows->each(fn($model) => $callback($model));
        }
    }

    public function map(Closure $callback): Collection
    {
        return $this->get()->map($callback);
    }

    public function primaryCol(): string
    {
        /* @var $model Entity */
        $model = $this->getModelClass();

        return $model::getPrimaryCol();
    }

    public function dd(): never
    {
        dd($this->toSql(true));
    }
}
