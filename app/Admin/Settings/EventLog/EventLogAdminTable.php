<?php

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

        if ($dateFrom && $dateTo) {
            $query->whereRaw('DATE(created_at) BETWEEN ? AND ?', [$dateFrom, $dateTo]);
        } elseif($dateFrom) {
            $query->whereRaw('DATE(created_at) = ?', [$dateFrom]);
        }

        return $query->paginate();
    }

    public function getLog($log): string
    {
        $logArr = json_decode($log, true);

        $parsed = collect($logArr)->map(fn($val, $key) => "<li>$key: $val</li>")->implode('');
        return "<ul>{$parsed}</ul>";
    }
}
