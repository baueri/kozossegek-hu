<?php

namespace Framework\Database;

use Framework\Http\Request;
use Framework\Support\DataSet;
use InvalidArgumentException;

class Builder
{
    private Database $db;

    private array $select = [];

    private array $table = [];

    private array $where = [];

    private array $join = [];

    private array $orderBy = [];

    private array $groupBy = [];

    private string $limit = '';

    private bool $distinct = false;

    private array $selectBindings = [];

    protected static array $macros = [];

    /**
     * Builder constructor.
     * @param Database $db
     */
    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * @return static
     */
    public static function query()
    {
        return app()->make(static::class);
    }

    /**
     *
     * @return array[]
     */
    public function get()
    {
        return $this->db->select(...$this->getBaseSelect());
    }

    public function fetchColumn()
    {
        return $this->db->fetchColumn(...$this->getBaseSelect());
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

    protected function getBaseSelect()
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

    protected function build()
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

    public function buildWhere(&$bindings = [])
    {
        $where = '';
        foreach ($this->where as $i => [$column, $operator, $value]) {
            if ($operator == 'in' || $operator == 'not in') {
                $in = implode(',', array_fill(0, count($value), '?'));
                $where .= sprintf("$column $operator (%s)", $in);
                $bindings = array_merge($bindings, $value);
            } elseif (is_callable($column)) {
                $builder = builder();

                $column($builder);

                $closureBindings = array_map(function ($where) {
                    return $where[2];
                }, $builder->getWhere());

                $closureWhere = $builder->buildWhere();

                $where .= "($closureWhere) $operator";

                if (is_array($closureBindings)) {
                    $bindings = array_merge($bindings, $closureBindings);
                }
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

    public function getWhere()
    {
        return $this->where;
    }

    public function first()
    {
        $this->limit(1);

        return $this->db->first(...$this->getBaseSelect());
    }

    public function limit($limit)
    {
        $this->limit = " limit $limit";

        return $this;
    }

    /**
     * @param int|null $limit
     * @param int|null $page
     * @return PaginatedResultSet
     */
    public function paginate(?int $limit = null, ?int $page = null)
    {
        $page = $page ?: request()->get('pg', 1);
        $limit = $limit ?? request()->get('per-page', 30);

        $total = $this->count();

        $rows = $this->limit(($page - 1) * $limit . ', ' . $limit)->get();

        return new PaginatedResultSet($rows, $limit, $page, $total);
    }

    public function orderBy($columns, ?string $order = null)
    {
        foreach ((array) $columns as $column) {
            $this->orderBy[] = $column . ($order ? ' ' . $order : '');
        }

        return $this;
    }

    public function groupBy($groupBy)
    {
        $this->groupBy[] = $groupBy;
        return $this;
    }

    public function from($table)
    {
        $this->table = [$table];

        return $this;
    }

    public function table($table)
    {
        return $this->from($table);
    }

    public function select($select = '*', $bindings = [])
    {
        $this->select = [$select];
        $this->selectBindings = array_merge($this->selectBindings, $bindings);

        return $this;
    }

    public function distinct()
    {
        $this->distinct = true;
        return $this;
    }

    public function addSelect($select, $bindings = [])
    {
        $this->select[] = $select;
        $this->selectBindings = array_merge($this->selectBindings, $bindings);

        return $this;
    }

    public function where($column, $operator = null, $value = null, $clause = 'and')
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

    public function whereRaw($where, $bindings = [], $clause = 'and')
    {
        $this->where[] = [$where, null, $bindings, $clause];

        return $this;
    }

    public function whereInSet($column, $value, $clause = 'and')
    {
        return $this->whereRaw("FIND_IN_SET(?, $column)", [$value], $clause);
    }

    public function whereNull($column, $clause = 'and')
    {
        return $this->whereRaw("$column IS NULL", [], $clause);
    }

    public function whereNotNull($column, $clause = 'and')
    {
        return $this->whereRaw("$column IS NOT NULL", [], $clause);
    }

    public function whereNotIn($column, array $values, $clause = 'and')
    {
        return $this->where($column, 'not in', $values, $clause);
    }

    public function whereIn($column, array $values, $clause = 'and')
    {
        $this->where($column, 'in', $values, $clause);

        return $this;
    }

    public function orWhere($column, $operator, $value)
    {
        return $this->where($column, $operator, $value, 'or');
    }

    public function orWhereRaw($where, $bindings = [])
    {
        return $this->whereRaw($where, $bindings, 'or');
    }

    public function orWhereInSet($column, $value)
    {
        return $this->whereInSet($column, $value, 'or');
    }

    public function join(string $table, string $on, string $joinMode = '')
    {
        return $this->joinRaw("{$joinMode} join {$table} on {$on}");
    }

    public function leftJoin(string $table, string $on)
    {
        return $this->join($table, $on, 'left');
    }

    public function rightJoin(string $table, string $on)
    {
        return $this->join($table, $on, 'right');
    }

    public function innerJoin(string $table, string $on)
    {
        return $this->join($table, $on, 'inner');
    }

    public function joinRaw(string $join)
    {
        $this->join[] = $join;
        return $this;
    }

    public function orderByFromRequest()
    {
        return $this->orderBy(request()->get('order_by', 'id'), request()->get('sort', 'desc'));
    }

    public function update(array $values)
    {
        $set = implode(', ', array_map(function ($column) {
            return "$column=?";
        }, array_keys($values)));

        [$query, $bindings] = $this->build();
        $table = implode(', ', $this->table);
        $allBindings = array_merge(array_values($values), $bindings);
        return $this->db->update("update {$table} set {$set}, {$query}", ...$allBindings);
    }

    public function insert(array $values)
    {
        $bindings = array_values($values);

        $table = implode(', ', $this->table);
        $columns = implode(',', array_keys($values));
        $values = implode(',', array_fill(0, count($values), '?'));

        $query = "insert into $table ($columns) values($values)";

        return $this->db->insert($query, $bindings);
    }

    public function updateOrInsert(array $where, array $values)
    {
        $onDuplicateArr = [];
        $allColumns = array_merge($where, $values);
        $columns = implode(',', array_keys($allColumns));
        foreach ($values as $key => $value) {
            $onDuplicateArr[] = $key . '=?';
        }

        $onDuplicate = implode(',', $onDuplicateArr);
        [$table] = $this->table;
        $whereValues = implode(',', array_fill(0, count($allColumns), '?'));
        $query = "insert into $table ($columns) values($whereValues) on duplicate key update $onDuplicate";

        $bindings = array_merge(array_values($allColumns), array_values($values));

        return $this->db->insert($query, $bindings);
    }

    public function delete()
    {
        [$query, $bindings] = $this->build();

        $tables = implode(', ', $this->table);

        return $this->db->delete("delete from {$tables} {$query}", $bindings);
    }

    public function exists()
    {
        $this->select('1 as `exists`');

        return (bool) $this->first()['exists'];
    }

    public function toSql($withBindings = false): string
    {
        [$query, $bindings] = $this->getBaseSelect();

        if (!$withBindings) {
            return $query;
        }

        return DatabaseHelper::getQueryWithBindings($query, $bindings);
    }

    private function getTable()
    {
        return implode(',', $this->table);
    }

    public function __call($method, $args)
    {
        $macro = $this->getMacro($method);

        $macro($this, ...$args);

        return $this;
    }

    public function macro($macroName, $callback)
    {
        $key = !$this->getTable() ? 'global' : $this->getTable();

        static::$macros[$key][$macroName] = $callback;

        return $this;
    }

    public function apply($macro, ...$args)
    {
        if (is_array($macro)) {
            foreach ($macro as $m) {
                $this->__call($m, $args);
            }

            return $this;
        }

        return $this->__call($macro, $args);
    }

    protected function getMacro($method)
    {
        if (isset(static::$macros[$this->getTable()][$method])) {
            return static::$macros[$this->getTable()][$method];
        } elseif (isset(static::$macros['global'][$method])) {
            return static::$macros['global'][$method];
        }

        throw new InvalidArgumentException("database builder macro $method not found");
    }

    public function __toString()
    {
        return $this->toSql();
    }
}
