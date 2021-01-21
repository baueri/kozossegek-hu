<?php

namespace Framework\Model;

use Framework\Database\PaginatedResultSetInterface;

class PaginatedModelCollection extends ModelCollection implements PaginatedResultSetInterface
{
    /**
     * @var int
     */
    protected $page;

    /**
     * @var int
     */
    protected $total;

    /**
     * @var int
     */
    private $perpage;

    public function __construct($rows, $perpage, $page = 1, $total = 0)
    {
        parent::__construct($rows);
        $this->page = $page;
        $this->total = $total;
        $this->perpage = $perpage;
    }

    /**
     * @inheritDoc
     */
    public function rows()
    {
        return $this->items;
    }

    /**
     * @inheritDoc
     */
    public function page()
    {
        return $this->page;
    }

    /**
     * @inheritDoc
     */
    public function total()
    {
        return $this->total;
    }

    public function perpage()
    {
        return $this->perpage;
    }
}
