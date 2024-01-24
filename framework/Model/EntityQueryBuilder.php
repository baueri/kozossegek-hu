<?php

declare(strict_types=1);

namespace Framework\Model;

use Closure;
use Framework\Database\Builder;
use Framework\Database\Repository\Events\ModelCreated;
use Framework\Database\Repository\Events\ModelDeleted;
use Framework\Event\EventDisptatcher;
use Framework\Model\Exceptions\ModelNotFoundException;
use Framework\Model\Exceptions\QueryBuilderException;
use Framework\Model\Relation\HasRelations;
use Framework\Support\Collection;
use Framework\Support\StringHelper;
use RuntimeException;

/**
 * @phpstan-template T of \Framework\Model\Entity
 */
abstract class EntityQueryBuilder
{
    use HasRelations;

    public const TABLE = null;

    public readonly Builder $builder;

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
    public static function getModelClass(): string
    {
        if (str_ends_with(static::class, 'ies')) {
            $singular = str_replace('ies', 'y', static::class);
        } else {
            $singular = substr(static::class, 0, strlen(static::class) -1);
        }

        [, $className] = namespace_split($singular);

        $modelClassName = "App\\Models\\{$className}";

        if (!class_exists($modelClassName)) {
            throw new RuntimeException("Could not instantiate {$modelClassName} from entity " . static::class);
        }

        return $modelClassName;
    }

    public static function query(): static
    {
        return new static();
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
     * @return Entity[]|ModelCollection<Entity>
     * @phpstan-return T[]|\Framework\Model\ModelCollection<T>
     */
    public function get(): ModelCollection
    {
        return $this->loadRelations(
            new ModelCollection(array_map(function ($row) {
                return $this->getInstance($row);
            }, $this->builder->get()))
        );
    }

    public function delete(): int
    {
        return $this->builder->delete();
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
        $class = static::getModelClass();

        return new $class($values);
    }

    /**
     * @phpstan-return \Framework\Model\Entity<T>|T|null
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
     * @param mixed $id
     * @return \Framework\Model\Entity|null
     * @phpstan-return \Framework\Model\Entity<T>|T|null
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
     * @phpstan-return \Framework\Model\Entity<T>|T|null
     */
    public function first(): ?Entity
    {
        return $this->loadRelations($this->getInstance($this->builder->first()));
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
        return $this->where(static::primaryCol(), $value);
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

    public function whereDoesnExist(Builder|EntityQueryBuilder $table, ?Closure $callback = null, string $clause = 'and'): static
    {
        $this->builder->whereDoesnExist($table instanceof EntityQueryBuilder ? $table->builder : $table, $callback, $clause);
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

        return static::query()->where(static::primaryCol(), $entity->getId())
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

        return $this->query()->where(static::primaryCol(), $entity->getId())->update($toUpdate);
    }

    public function insert(array $values): int|string
    {
        return $this->builder->insert($values);
    }

    public function updateOrInsert(array $where, array $values = []): int|string
    {
        return $this->builder->updateOrInsert($where, $values);
    }

    public function deleteModel($model, bool $hardDelete = false): bool
    {
        if (is_numeric($model)) {
            $model = $this->find($model);
        }

        if (property_exists($model, 'deleted_at') && !$hardDelete) {
            return (bool) $this->save($model, ['deleted_at' => date('Y-m-d H:i:s')]);
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
    public function create(array|Entity $values)
    {
        $model = $values instanceof Entity ? $values : null;
        $toSave = $model ? $model->getAttributes() : $values;

        $insertId = $this->insert($toSave);

        if (isset($model)) {
            $model->{self::primaryCol()} = $insertId;
        } else {
            $model = $this->getInstance($toSave + [static::primaryCol() => $insertId]);
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

    public static function primaryCol(): string
    {
        /* @var $model Entity */
        $model = static::getModelClass();

        return $model::getPrimaryCol();
    }

    public function dd(): never
    {
        dd($this->toSql(true));
    }
}
