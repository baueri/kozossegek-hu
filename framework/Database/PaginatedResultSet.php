<?php


namespace Framework\Database;


use Framework\Support\Collection;

class PaginatedResultSet extends Collection implements PaginatedResultSetInterface
{
    private $page;

    private $total;

    /**
     * @var int
     */
    private $perpage;

    public function __construct($items, $perpage = 10, $page = 1, $total = 0)
    {
        parent::__construct($items);
        $this->page = $page;
        $this->total = $total;
        $this->perpage = $perpage;
    }

    public function rows()
    {
        return $this->items;
    }

    public function page()
    {
        return $this->page;
    }

    public function total()
    {
        return $this->total;
    }

    public function perpage()
    {
        return $this->perpage;
    }
}