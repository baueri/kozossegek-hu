<?php

declare(strict_types=1);

namespace Framework\Database;

use Framework\Support\Collection;

class PaginatedResultSet extends Collection implements PaginatedResultSetInterface
{
    private int $page;

    private int $total;

    private int $perpage;

    public function __construct($items, $perpage = 10, $page = 1, $total = 0)
    {
        parent::__construct($items);
        $this->page = (int) $page;
        $this->total = (int) $total;
        $this->perpage = (int) $perpage;
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
