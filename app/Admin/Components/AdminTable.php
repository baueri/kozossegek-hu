<?php

namespace App\Admin\Components;

use Framework\Database\PaginatedResultSetInterface;
use Framework\Dispatcher\Dispatcher;
use Framework\Http\Request;
use Framework\Http\View\ViewInterface;
use Framework\Support\StringHelper;

abstract class AdminTable
{
    /**
     * @var ViewInterface
     */
    private $view;

    /**
     * @var array
     */
    protected $columns = [];

    protected $centeredColumns = [];

    /**
     * @var Request
     */
    protected $request;

    /**
     * AdminTable constructor.
     * @param ViewInterface $view
     * @param Request $request
     */
    public function __construct(ViewInterface $view, Request $request)
    {
        if (!$this->columns) {
            throw new \InvalidArgumentException('missing columns for ' . static::class);
        }

        $this->view = $view;
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
        return $this->view->view('admin.partials.table', $model);
    }

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

    protected function transformRowColumn($row, $column)
    {
        $method = StringHelper::camel("get" . ucfirst($column));
        $value = property_exists($row, $column) ? $row->{$column} : null;

        if (!method_exists($this, $method)) {
            return $value;
        }

        return call_user_func_array([$this, $method], [$value, $row]);
    }

    public function __toString()
    {
        try {
            return $this->render();
        } catch (\Exception $e) {
            app()->get(Dispatcher::class)->handleError($e);
        }
    }
}