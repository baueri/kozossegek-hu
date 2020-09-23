<?php

namespace Framework\Database\Schema\Action;

use Framework\Database\Schema\Table;

class AddIndex extends Action
{
    protected $columns;

    protected $options;

    public function __construct(Table $table, $columns, $options = [])
    {
        parent::__construct($table);
        $this->columns = $columns;
        $this->options = $options;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function getCommand()
    {
        return 'INDEX `' . implode('_', $this->columns) . '` (' . implode(',', $this->columns) . ')';
    }
}