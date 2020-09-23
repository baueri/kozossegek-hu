<?php

namespace Framework\Database\Schema\Action;

use Framework\Database\Schema\Table;

abstract class Action implements ActionInterface
{
    /**
     * @var Table
     */
    protected Table $table;

    /**
     * Action constructor.
     * @param Table $table
     */
    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    /**
     * @return Table
     */
    public function getTable(): Table
    {
        return $this->table;
    }
}