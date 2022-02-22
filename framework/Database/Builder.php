<?php

namespace Framework\Database;

use Closure;

final class Builder
{

    private array $select = [];

    private array $table = [];

    private array $where = [];

    private array $join = [];

    private array $orderBy = [];

    private array $groupBy = [];

    private string $limit = '';

    private bool $distinct = false;

    private array $selectBindings = [];

    private static array $macros = [];

    public function __construct(private Database $db)
    {
    }

    /**
     * @return array<int, array>
     */
    public function get(): array
    {
        return $this->db->select(...$this->getBaseSelect());
    }

    /**
     * @return int
     */
    public function count(): int
    {
        $oldSelect = $this->select;

        $oldSelectBindings = $this->selectBindings;
        $this->selectBindings = [];

        $oldOrders = $this->orderBy;
        $this->orderBy = [];

        $this->select = ['count(*) as cnt'];

        if ($this->groupBy) {
            $results = $this->db->select(...$this->getBaseSelect());
            $count = count($results);
        } else {
            $count = $this->db->first(...$this->getBaseSelect())['cnt'];
        }

        $this->select = $oldSelect;
        $this->selectBindings = $oldSelectBindings;
        $this->orderBy = $oldOrders;

        return (int) $count;
    }

    private function getBaseSelect(): array
    {
        [$query, $bindings] = $this->build();
        $bindings = array_merge($this->selectBindings, $bindings);

        $distinct = $this->distinct ? 'DISTINCT ' : '';
        $base = sprintf(
            'select %s%s from %s ',
            $distinct,
            implode(', ', $this->select ?: ['*']),
            implode(', ', $this->table)
        );

        return [$base . $query, $bindings];
    }

    private function build(): array
    {
        $bindings = [];

        $query = '';

        if ($this->join) {
            $query .= ' ' . implode(' ', $this->join);
        }

        if ($this->where) {
            $query .= ' where ' . $this->buildWhere($bindings);
        }

        if ($this->groupBy) {
            $query .= ' group by ' . implode(', ', $this->groupBy);
        }

        if ($this->orderBy) {
            $query .= sprintf(' order by %s', implode(', ', $this->orderBy));
        }

        $query .= $this->limit;

        return [$query, $bindings];
    }

    public function buildWhere(?array &$bindings = []): string
    {
        $where = '';
        foreach ($this->where as $i => [$column, $operator, $value]) {
            if ($operator == 'in' || $operator == 'not in') {
                $in = implode(',', array_fill(0, count($value), '?'));
                $where .= sprintf("$column $operator (%s)", $in);
                $bindings = array_merge($bindings, $value);
            } elseif (is_callable($column)) {
                $builder = new self($this->db);

                $column($builder);

                $closureBindings = array_map(function ($where) {
                    return $where[2];
                }, $builder->getWhere());

                $closureWhere = $builder->buildWhere();

                $where .= "($closureWhere) $operator";

                $bindings = array_merge($bindings, $closureBindings);
            } elseif ($operator) {
                $where .= sprintf('%s %s ?', $column, $operator);
                $bindings[] = $value;
            } else {
                $where .= $column;
                if (is_array($value)) {
                    $bindings = array_merge($bindings, $value);
                }
            }

            if (isset($this->where[$i + 1])) {
                $clause = $this->where[$i + 1][3];
                $where .= " $clause ";
            }
        }


        return $where;
    }

    private function getWhere(): array
    {
        return $this->where;
    }

    public function first()
    {
        $this->limit(1);

        return $this->db->first(...$this->getBaseSelect());
    }

    public function limit(int|string $limit): self
    {
        $this->limit = " limit {$limit}";

        return $this;
    }

    public function paginate(?int $limit = null, ?int $page = null): PaginatedResultSet
    {
        $page = $page ?: request()->get('pg', 1);
        $limit = $limit ?? request()->get('per-page', 30);

        $total = $this->count();

        $rows = $this->limit(($page - 1) * $limit . ', ' . $limit)->get();

        return new PaginatedResultSet($rows, $limit, $page, $total);
    }

    public function orderBy($columns, ?string $order = null): self
    {
        foreach ((array) $columns as $column) {
            $this->orderBy[] = $column . ($order ? ' ' . $order : '');
        }

        return $this;
    }

    public function groupBy($groupBy): self
    {
        $this->groupBy[] = $groupBy;
        return $this;
    }

    public function from($table): self
    {
        $this->table = [$table];

        return $this;
    }

    public function select($select = '*', $bindings = []): self
    {
        $this->select = [$select];
        $this->selectBindings = array_merge($this->selectBindings, $bindings);

        return $this;
    }

    public function distinct(): self
    {
        $this->distinct = true;
        return $this;
    }

    public function addSelect($select, $bindings = []): self
    {
        $this->select[] = $select;
        $this->selectBindings = array_merge($this->selectBindings, $bindings);

        return $this;
    }

    public function where($column, $operator = null, $value = null, $clause = 'and'): self
    {
        if (is_callable($column)) {
            $this->where[] = [$column, null, null, $operator ?: 'and'];
            return $this;
        }

        if (is_null($value)) {
            $value = $operator;
            $operator = '=';
        }

        $this->where[] = [$column, $operator, $value, $clause];

        return $this;
    }

    public function whereRaw($where, $bindings = [], $clause = 'and'): self
    {
        $this->where[] = [$where, null, $bindings, $clause];

        return $this;
    }

    public function whereInSet($column, $value, $clause = 'and'): self
    {
        return $this->whereRaw("FIND_IN_SET(?, $column)", [$value], $clause);
    }

    public function whereNull($column, $clause = 'and'): self
    {
        return $this->whereRaw("$column IS NULL", [], $clause);
    }

    public function whereNotNull($column, $clause = 'and'): self
    {
        return $this->whereRaw("$column IS NOT NULL", [], $clause);
    }

    public function whereNotIn($column, array $values, $clause = 'and'): self
    {
        return $this->where($column, 'not in', $values, $clause);
    }

    public function whereIn($column, $values, $clause = 'and'): self
    {
        if (!$values) {
            return $this->whereRaw('1 = 2');
        }

        return $this->where($column, 'in', collect($values)->all(), $clause);
    }

    public function orWhere($column, $operator = null, $value = null): self
    {
        return $this->where($column, $operator, $value, 'or');
    }

    public function orWhereRaw($where, $bindings = []): self
    {
        return $this->whereRaw($where, $bindings, 'or');
    }

    public function orWhereInSet($column, $value): self
    {
        return $this->whereInSet($column, $value, 'or');
    }

    /**
     * @param string $table
     * @param Closure $callback
     * @param string $clause
     * @return $this
     */
    public function whereExists(string $table, Closure $callback, string $clause = 'and'): self
    {
        $callback($builder = (new self($this->db))->select('1')->from($table));

        [$query, $bindings] = $builder->getBaseSelect();
        $this->whereRaw("EXISTS ({$query})", $bindings, $clause);

        return $this;
    }

    public function orWhereExists(string $table, Closure $callback): self
    {
        return $this->whereExists($table, $callback, 'or');
    }

    public function join(string $table, string $on, string $joinMode = ''): self
    {
        return $this->joinRaw("{$joinMode} join {$table} on {$on}");
    }

    public function leftJoin(string $table, string $on): self
    {
        return $this->join($table, $on, 'left');
    }

    public function rightJoin(string $table, string $on): self
    {
        return $this->join($table, $on, 'right');
    }

    public function innerJoin(string $table, string $on): self
    {
        return $this->join($table, $on, 'inner');
    }

    public function joinRaw(string $join): self
    {
        $this->join[] = $join;
        return $this;
    }

    public function orderByFromRequest(): self
    {
        return $this->orderBy(request()->get('order_by', 'id'), request()->get('sort', 'desc'));
    }

    public function update(array $values): int
    {
        $set = implode(', ', array_map(function ($column) {
            return "$column=?";
        }, array_keys($values)));

        [$query, $bindings] = $this->build();
        $table = implode(', ', $this->table);
        $allBindings = array_merge(array_values($values), $bindings);
        return $this->db->update("update {$table} set {$set} {$query}", ...$allBindings);
    }

    public function insert(array $values): int|string
    {
        $bindings = array_values($values);

        $table = implode(', ', $this->table);
        $columns = implode(',', array_keys($values));
        $values = implode(',', array_fill(0, count($values), '?'));

        $query = "insert into $table ($columns) values($values)";

        return $this->db->insert($query, $bindings);
    }

    public function updateOrInsert(array $where, array $values = [])
    {
        foreach ($where as $column => $value) {
            $this->where($column, $value);
        }

        if ($this->exists()) {
            return $this->update($values);
        }

        return $this->insert(array_merge($where, $values));
    }

    public function delete(): int
    {
        [$query, $bindings] = $this->build();

        $tables = implode(', ', $this->table);

        return $this->db->delete("delete from {$tables} {$query}", $bindings);
    }

    public function exists(): bool
    {
        $this->select('1 as `exists`');

        return isset($this->first()['exists']);
    }

    public function toSql($withBindings = false): string
    {
        [$query, $bindings] = $this->getBaseSelect();

        if (!$withBindings) {
            return $query;
        }

        return DatabaseHelper::getQueryWithBindings($query, $bindings);
    }

    public function getTable(): string
    {
        return implode(',', $this->table);
    }

    public function __call($method, $args)
    {
        $this->applyMacro($method, $args);

        return $this;
    }

    public function macro($macroName, $callback): self
    {
        $key = !$this->getTable() ? 'global' : $this->getTable();

        self::$macros[$key][$macroName] = $callback;

        return $this;
    }

    public function apply($macro, ...$args): self
    {
        if (is_array($macro)) {
            foreach ($macro as $m) {
                $this->applyMacro($m, $args);
            }

            return $this;
        }

        return $this->applyMacro($macro, $args);
    }

    protected function applyMacro($macro, $args): self
    {
        $callback = $this->getMacro($macro);

        if ($callback) {
            $callback($this, ...$args);
        }

        return $this;
    }

    protected function getMacro($method)
    {
        if (isset(self::$macros[$this->getTable()][$method])) {
            return self::$macros[$this->getTable()][$method];
        } elseif (isset(self::$macros['global'][$method])) {
            return self::$macros['global'][$method];
        }

        return null;
    }

    public function __toString()
    {
        return $this->toSql();
    }
}
