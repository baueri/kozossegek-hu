<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 10/11/18
 * Time: 16:01
 */

namespace Framework\Database\Schema\Action;


use Framework\Database\Schema\Table;

class RemoveColumn extends Action
{
    protected $name;

    public function __construct(Table $table, $name)
    {
        parent::__construct($table);
    }

    public function getCommand()
    {
        return 'DROP COLUMN ' . $this->name;
    }
}