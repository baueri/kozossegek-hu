<?php


namespace App\Admin\Components\DebugBar;


use Framework\Database\DatabaseHelper;
use Framework\Database\QueryHistory;

class QueryHistoryTab extends DebugBarTab
{
    /**
     * @var QueryHistory
     */
    private $queryHistory;

    /**
     * QueryHistoryTab constructor.
     * @param QueryHistory $queryHistory
     */
    public function __construct(QueryHistory $queryHistory)
    {

        $this->queryHistory = $queryHistory;
    }

    public function getName()
    {
        $count = $this->queryHistory->getQueryHistory()->count();
        return "lekérdezések ($count)";
    }

    public function render()
    {
        $time = round($this->queryHistory->getExecutionTime(), 4);
        $queries = $this->queryHistory->getQueryHistory()->map(function($row){
            $row[0] = DatabaseHelper::getQueryWithBindings($row[0], $row[1]);
            return $row;
        });

        return view('admin.partials.debugbar.query-history', ['queries' => $queries, 'total_time' => $time]);
    }
}