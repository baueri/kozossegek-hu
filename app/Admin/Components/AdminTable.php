<?php

namespace App\Admin\Components;

use Exception;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Dispatcher\Dispatcher;
use Framework\Http\Request;
use Framework\Support\StringHelper;
use InvalidArgumentException;

abstract class AdminTable
{
    /**
     * @var array
     */
    protected $columns = [];

    /**
     * @var array
     */
    protected $centeredColumns = [];

    /**
     * @var Request
     */
    protected $request;

    /**
     * AdminTable constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        if (!$this->columns) {
            throw new InvalidArgumentException('missing columns for ' . static::class);
        }

        $this->request = $request;
    }

    /**
     * @return PaginatedResultSetInterface
     */
    abstract protected function getData(): PaginatedResultSetInterface;

    /**
     * @return string
     */
    public function render()
    {
        $data = $this->getData();
        $model = [
            'columns' => $this->columns,
            'centered_columns' => $this->centeredColumns,
            'rows' => $this->transformData($data->rows()),
            'adminTable' => $this,
            'total' => $data->total(),
            'page' => $data->page(),
            'perpage' => $data->perpage()
        ];
        return view('admin.partials.table', $model);
    }

    /**
     * @param $rows
     * @return array
     */
    protected function transformData($rows)
    {
        $transformed = [];

        foreach ($rows as $i => $row) {
            foreach (array_keys($this->columns) as $column) {
                $transformed[$i][$column] = $this->transformRowColumn($row, $column);
            }
        }

        return $transformed;
    }

    /**
     * @param $row
     * @param $column
     * @return mixed|null
     */
    protected function transformRowColumn($row, $column)
    {
        $method = StringHelper::camel("get" . ucfirst($column));
        $value = property_exists($row, $column) ? $row->{$column} : null;

        if (!method_exists($this, $method)) {
            return $value;
        }

        return call_user_func_array([$this, $method], [$value, $row]);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        try {
            return $this->render();
        } catch (Exception $e) {
            app()->get(Dispatcher::class)->handleError($e);

            return '';
        }
    }
}