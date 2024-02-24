<?php

namespace App\Admin\Components\DebugBar;

use Framework\Database\DatabaseHelper;
use Framework\Database\QueryLog;

class QueryHistoryTab extends DebugBarTab
{
    public function __construct(
        public QueryLog $queryHistory
    ) {
    }

    public function getTitle(): string
    {
        $count = $this->queryHistory->getQueryHistory()->count();
        return "lekérdezések ($count)";
    }

    public function icon(): string
    {
        return 'fa fa-database';
    }

    public function render(): string
    {
        $time = $this->getTotalTime();
        $queries = $this->queryHistory->getQueryHistory()->map(function ($row) {
            $row[0] = DatabaseHelper::getQueryWithBindings($row[0], $row[1]);
            $row[2] = round($row[2] * 10000, 2);
            return $row;
        });

        return view('admin.partials.debugbar.query-history', ['queries' => $queries, 'total_time' => $time]);
    }

    public function getTotalTime(): float
    {
        return round($this->queryHistory->getExecutionTime(), 3);
    }
}
