<?php

declare(strict_types=1);

namespace Framework\Database;

use Framework\Support\Collection;

class PaginatedResultSet extends Collection implements PaginatedResultSetInterface
{
    private readonly int $page;

    private readonly int $total;

    private readonly int $perpage;

    public function __construct($items, int $perpage = 10, int $page = 1, $total = 0)
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

    public function page(): int
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
