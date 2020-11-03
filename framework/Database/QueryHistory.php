<?php


namespace Framework\Database;


use Framework\Support\Collection;

class QueryHistory
{
    /**
     * @var Collection
     */
    protected $queryHistory;

    public function __construct()
    {
        $this->queryHistory = collect();
    }

    public function pushQuery($query, $bindings, $time)
    {
        $this->queryHistory->push([$query, $bindings, $time]);
    }

    /**
     * @return Collection
     */
    public function getQueryHistory()
    {
        return $this->queryHistory;
    }

    /**
     * @return array
     */
    public function getLastQuery()
    {
        return $this->queryHistory->last();
    }

    public function getExecutionTime()
    {
        return $this->queryHistory->reduce(function($time, $row){
            $time += $row[2];
            return $time;
        }, 0);

    }
}