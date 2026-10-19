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
        return 'Queries';
    }

    public function getBadge(): ?int
    {
        return $this->queryHistory->getQueryLog()->count();
    }

    public function icon(): string
    {
        return 'fa fa-database';
    }

    public function render(): string
    {
        $time = $this->getTotalTime();
        $queries = $this->queryHistory->getQueryLog()->map(function ($row) {
            return [
                'sql'  => self::highlightSql(DatabaseHelper::getQueryWithBindings($row[0], $row[1])),
                'time' => round($row[2] * 10000, 2),
            ];
        });

        return view('admin.partials.debugbar.query-history', ['queries' => $queries, 'total_time' => $time]);
    }

    public function getTotalTime(): float
    {
        return round($this->queryHistory->getExecutionTime(), 3);
    }

    private static function highlightSql(string $sql): string
    {
        $html = htmlspecialchars($sql, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return preg_replace(
            '/\b(SELECT|FROM|WHERE|INNER|LEFT|RIGHT|FULL|OUTER|JOIN|ON|AND|OR|NOT|IN|IS|NULL|AS|DISTINCT|ORDER\s+BY|GROUP\s+BY|HAVING|LIMIT|OFFSET|INSERT|INTO|VALUES|UPDATE|SET|DELETE|CREATE|TABLE|INDEX|COUNT|SUM|AVG|MAX|MIN|UNION|ALL|WITH|CASE|WHEN|THEN|ELSE|END|EXISTS|BETWEEN|LIKE|ILIKE|COALESCE|NULLIF|CAST|CONCAT)\b/i',
            '<span class="dbg-sql-kw">$1</span>',
            $html
        );
    }
}
