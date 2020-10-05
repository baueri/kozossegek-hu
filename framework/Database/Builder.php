<?php


namespace Framework\Database;


use Framework\Http\Request;

class Builder
{
    /**
     * @var Database
     */
    private $db;

    private $select = [];

    private $table = [];

    private $where = [];

    private $orderBy = [];

    private $limit;

    private $distinct = false;

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
    public function count()
    {
        $oldSelect = $this->select;

        $this->select = ['count(*) as cnt'];

        $count = $this->db->first(...$this->getBaseSelect())['cnt'];

        $this->select = $oldSelect;

        return $count;
    }

    protected function getBaseSelect()
    {
        [$query, $bindings] = $this->build();

        $base = sprintf('select %s from %s ',
            implode(', ', $this->select ?: ['*']),
            implode(', ', $this->table)
        );

        return [$base . $query, $bindings];
    }

    protected function build()
    {

        $bindings = [];

        $query = '';

        if ($this->where) {
            $query .= ' where ' . $this->buildWhere($bindings);
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
        foreach ($this->where as $i => [$column, $operator, $value, $clause]) {

            if ($operator == 'in') {
                $in = implode(',', array_fill(0, count($value), '?'));
                $where .= sprintf("$column $operator (%s)", $in);
                $bindings = array_merge($bindings, $value);
            } elseif(is_callable($column)) {

                $builder = builder();

                $column($builder);

                $closureBindings = array_map(function($where){
                    return $where[2];
                }, $builder->getWhere());

                $closureWhere = $builder->buildWhere();

                $where .= "($closureWhere) $operator";

                if(is_array($closureBindings)) {
                    $bindings = array_merge($bindings, $closureBindings);
                }

            } elseif($operator) {
                $where .= sprintf('%s %s ?', $column, $operator);
                $bindings[] = $value;
            } else {
                $where .= $column;
            }

            if (isset($this->where[$i + 1])) {
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
     * @param $limit
     * @param $page
     * @return PaginatedResultSet
     */
    public function paginate($limit, $page = null)
    {
        $page = $page ?: app()->get(Request::class)['pg'] ?: 1;

        $total = $this->count();

        $rows = $this->limit(($page-1) * $limit . ', ' . $limit)->get();

        return new PaginatedResultSet($rows, $limit, $page, $total);
    }

    public function orderBy($column, $order = null)
    {
        $this->orderBy[] = $column . ($order ? ' ' . $order : '');

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

    public function select($select = '*')
    {
        $this->select = [$select];
        return $this;
    }

    public function distinct()
    {
        $this->distinct = true;
        return $this;
    }

    public function addSelect($select)
    {
        $this->select = $select;
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

    public function whereNull($column, $clause = 'and')
    {
        return $this->whereRaw("$column IS NULL", [], $clause);
    }

    public function whereNotNull($column, $clause = 'and')
    {
        return $this->whereRaw("$column IS NOT NULL", [], $clause);
    }

    public function whereIn($column, array $values)
    {
        $this->where($column, 'in', $values);

        return $this;
    }

    public function update(array $values)
    {
        $set = implode(', ', array_map(function ($column) {
            return "$column=?";
        }, array_keys($values)));

        [$query, $bindings] = $this->build();

        $base = sprintf('update %s set %s',
        implode(', ', $this->table),
        $set);

        return $this->db->update($base . $query, ...array_merge(array_values($values), $bindings));
    }

    public function insert(array $values)
    {
        $bindings = array_values($values);

        $query = sprintf('insert into %s (%s) values (%s)',
            implode(', ', $this->table),
            implode(',', array_keys($values)),
            implode(',', array_fill(0, count($values), '?')));

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
        $query = sprintf('insert into %s (%s) values(%s) on duplicate key update %s',
            $table,
            $columns,
            $whereValues,
            $onDuplicate
        );

        $bindings = array_merge(array_values($allColumns), array_values($values));

        return $this->db->insert($query, $bindings);
    }

    public function delete()
    {

        [$query, $bindings] = $this->build();

        $base = sprintf('delete from %s', implode(', ', $this->table));

        return $this->db->delete($base . $query, $bindings);
    }

    public function exists()
    {
        $this->select('1 as `exists`');

        return (bool) $this->first()['exists'];
    }

    public function toSql($withBindings = false)
    {
        [$query, $bindings] = $this->getBaseSelect();

        if (!$withBindings) {
            return $query;
        }

        

        return str_replace(['?'], array_map(function($binding){
            return "$binding";
        }, $bindings), $query);

    }
}
