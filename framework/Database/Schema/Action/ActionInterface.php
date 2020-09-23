<?php

namespace Framework\Database\Schema\Action;

use Framework\Database\Schema\Table;

interface ActionInterface
{
    public function getTable() : Table;

    public function getCommand();
}