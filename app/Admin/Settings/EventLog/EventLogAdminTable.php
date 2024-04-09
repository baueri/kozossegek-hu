<?php

declare(strict_types=1);

namespace App\Admin\Settings\EventLog;

use App\Admin\Components\AdminTable\PaginatedAdminTable;
use App\Repositories\EventLogs;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Http\Request;

class EventLogAdminTable extends PaginatedAdminTable
{
    protected array $columns = [
        'id' => '#',
        'type' => 'típus',
        'log' => 'adat',
        'user_id' => 'user',
        'created_at' => 'dátum'
    ];

    public function __construct(
        Request $request,
        protected readonly EventLogs $repository
    ) {
        parent::__construct($request);
    }

    protected function getData(): PaginatedResultSetInterface
    {
        $query = $this->repository
            ->query()
            ->orderBy(...$this->order);

        $dateFrom = $this->request['date_from'];
        $dateTo = $this->request['date_to'];
        $type = $this->request['type'];
        $request_page = $this->request['request_page'];

        if ($dateFrom && $dateTo) {
            $query->whereRaw('DATE(created_at) BETWEEN ? AND ?', [$dateFrom, $dateTo]);
        } elseif($dateFrom) {
            $query->whereRaw('DATE(created_at) = ?', [$dateFrom]);
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($request_page) {
            $query->where('`log`', 'LIKE', "%\"page\":%{$request_page}%");
        }

        return $query->paginate();
    }

    public function getLog($log): string
    {
        $logArr = json_decode($log, true);

        $parsed = collect($logArr)->map(function($val, $key) {
            if (is_array($val)) {
                $val = json_encode($val);
            }
            return "<li>$key: $val</li>";
        })->implode('');
        return "<ul>{$parsed}</ul>";
    }
}
