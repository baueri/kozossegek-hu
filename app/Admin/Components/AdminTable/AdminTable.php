<?php

namespace App\Admin\Components\AdminTable;

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

    protected array $order = ['id', 'desc'];

    /**
     * @var array
     */
    protected array $centeredColumns = [];

    protected array $sortableColumns = ['id'];

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var string[]
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * AdminTable constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        if (!$this->columns) {
            throw new InvalidArgumentException('missing columns for ' . static::class);
        }

        $this->order = [$request->get('order_by', 'id'), $request->get('sort', 'desc')];

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
            'columns' => $this->getColumns(),
            'order' => $this->order,
            'sort_order' => $this->order[1] == 'asc' || $this->order[0] ? 'desc' : 'asc',
            'centered_columns' => $this->centeredColumns,
            'sortable_columns' => $this->sortableColumns,
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
            foreach (array_keys($this->getColumns()) as $column) {
                $transformed[$i][$column] = $this->transformRowColumn($row, $column);
            }
        }

        return $transformed;
    }

    private function getColumns()
    {
        $columns = $this->columns;

        if ($this instanceof Deletable) {
            $columns['delete'] = '<i class="fa fa-trash"></i>';
        }

        return $columns;
    }

    /**
     * @param $row
     * @param $column
     * @return mixed|null
     */
    protected function transformRowColumn($row, $column)
    {
        $method = StringHelper::camel("get" . ucfirst($column));
        if (is_object($row)) {
            $value = property_exists($row, $column) ? $row->{$column} : null;
        } else {
            $value = $row[$column] ?? null;
        }


        if (!method_exists($this, $method)) {

            if ($this instanceof Editable && $column == $this->getEditColumn()) {
                return $this->getEdit($value, $row);
            }

            if (in_array($column, $this->dates)) {
                if (!$value) {
                    return '-';
                }
                return date('Y.m.d H:i', strtotime($value));
            }

            return $value;
        }

        return call_user_func_array([$this, $method], [$value, $row]);
    }

    protected function getEdit($value, $model, $excerpt = null)
    {
        $url = $this->getEditUrl($model);

        $text = $value;

        if ($excerpt) {
            $text = StringHelper::shorten($value, $excerpt, '...');
        }

        return "<a href='$url' title='{$value}'>$text</a>";
    }

    protected function getDelete($t, $model, $title = 'lomtárba')
    {
        $url = $this->getDeleteUrl($model);

        return $this->getDeleteColumn($url, $title);
    }

    protected function getDeleteColumn(string $url, string $title)
    {
        return "<a href='$url' title='$title'><i class='fa fa-trash text-danger'></i></a>";
    }

    protected static function getCheckIcon(?string $title)
    {
        return "<i class='fa fa-check-circle text-success' title='$title'></i>";
    }

    protected static function getBanIcon(?string $title)
    {
        return "<i class='fa fa-ban text-danger' title='$title'></i>";
    }

    protected static function excerpt(string $text, $withTooltip = true)
    {
        $shorten = StringHelper::shorten($text, 20, '...');
        $tooltip = $withTooltip ? $text : '';
        return "<span title='$tooltip'>$shorten</span>";
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
