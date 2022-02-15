<?php

namespace Framework\Model;

use Framework\Database\PaginatedResultSetInterface;

class PaginatedModelCollection extends ModelCollection implements PaginatedResultSetInterface
{
    protected int $page;

    protected int $total;

    private int $perpage;

    public function __construct($rows, $perpage, $page = 1, $total = 0)
    {
        parent::__construct($rows);
        $this->page = $page;
        $this->total = $total;
        $this->perpage = $perpage;
    }

    public function rows(): array
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

    public function perpage(): int
    {
        return $this->perpage;
    }
}
