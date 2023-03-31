<?php

namespace App\Admin\Components\AdminTable;

use Exception;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Dispatcher\Dispatcher;
use Framework\Http\Request;
use Framework\Support\StringHelper;
use InvalidArgumentException;

abstract class PaginatedAdminTable
{
    protected array $columns = [];

    protected array $order;

    protected string $defaultOrderColumn = 'id';

    protected string $defaultOrder = 'desc';

    protected int $perpage;

    protected array $centeredColumns = [];

    protected array $sortableColumns = [];

    protected array $columnClasses = [];

    protected bool $withPager = true;

    /**
     * @var string[]
     */
    protected array $dates = ['created_at', 'updated_at', 'deleted_at'];

    public function __construct(
        public readonly Request $request
    ) {
        if (!$this->columns) {
            throw new InvalidArgumentException('missing columns for ' . static::class);
        }

        $this->order = [
            $request->get('order_by', $this->defaultOrderColumn),
            $request->get('sort', $this->defaultOrder)
        ];

        $this->perpage = (int) ($request['per-page'] ?? 20);
    }

    abstract protected function getData(): PaginatedResultSetInterface;

    public function render(): string
    {
        $data = $this->getData();

        $model = [
            'columns' => $this->getColumns(),
            'order' => $this->order,
            'sort_order' => $this->order[1] == 'asc' || $this->order[0] ? 'desc' : 'asc',
            'centered_columns' => $this->centeredColumns,
            'sortable_columns' => $this->sortableColumns,
            'column_classes' => $this->columnClasses,
            'rows' => $this->transformData($data->rows()),
            'adminTable' => $this,
            'total' => $data->total(),
            'page' => $data->page(),
            'perpage' => $data->perpage(),
            'with_pager' => $this->withPager
        ];

        return view('admin.partials.table', $model);
    }

    protected function transformData($rows): array
    {
        $transformed = [];

        foreach ($rows as $i => $row) {
            foreach (array_keys($this->getColumns()) as $column) {
                $transformed[$i][$column] = $this->transformRowColumn($row, $column);
            }
        }

        return $transformed;
    }

    private function getColumns(): array
    {
        $columns = $this->columns;

        if (count(array_filter(array_keys($columns), 'is_string')) === 0) {
            $columns = array_combine($columns, $columns);
        }

        if ($this instanceof Deletable) {
            $columns['delete'] = '<i class="fa fa-trash-alt"></i>';
        }

        return $columns;
    }

    protected function transformRowColumn($row, string $column): mixed
    {
        $method = StringHelper::camel("get" . ucfirst($column));
        if (is_object($row)) {
            $value = $row->{$column} ?? null;
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

    protected function getEdit($value, $model, $excerpt = null): string
    {
        $url = $this->getEditUrl($model);

        $text = $value;

        if ($excerpt) {
            $text = StringHelper::shorten($value, $excerpt, '...');
        }

        $icon = self::getIcon('fa fa-edit');

        return "<a href='{$url}' title='{$value}'>{$icon} {$text}</a>";
    }

    protected function getDelete($t, $model, $title = 'lomtárba'): string
    {
        $url = $this->getDeleteUrl($model);

        return $this->getDeleteColumn($url, $title);
    }

    protected function getDeleteColumn(string $url, string $title): string
    {
        return "<a href='$url' title='$title'><i class='fa fa-trash-alt text-danger'></i></a>";
    }

    protected static function getCheckIcon(string $title = ''): string
    {
        return static::getIcon('fa fa-check-circle text-success', $title);
    }

    protected static function getBanIcon(string $title = ''): string
    {
        return static::getIcon('fa fa-ban text-danger', $title);
    }

    protected static function getIcon(string $class, string $title = ''): string
    {
        return "<i class='{$class}' title='$title'></i>";
    }

    protected static function excerpt(string $text, bool $withTooltip = true, int $limit = 20): string
    {
        $shorten = StringHelper::shorten($text, $limit, '...');
        $tooltip = $withTooltip ? $text : '';
        return "<span title='$tooltip'>$shorten</span>";
    }

    protected function getLink(string $url, ?string $text, string $title = ''): string
    {
        $titleAttr = $title ? " title='{$title}'" : '';
        return "<a href='{$url}'{$titleAttr}>$text</a>";
    }

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
