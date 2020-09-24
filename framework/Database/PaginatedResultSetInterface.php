<?php


namespace Framework\Database;


use ArrayAccess;
use Countable;
use IteratorAggregate;

interface PaginatedResultSetInterface extends ArrayAccess, Countable, IteratorAggregate
{
    /**
     * @return ArrayAccess|Countable|IteratorAggregate|[]|array
     */
    public function rows();

    /**
     * @return int
     */
    public function page();

    /**
     * @return int
     */
    public function total();

    /**
     * @return int
     */
    public function perpage();
}