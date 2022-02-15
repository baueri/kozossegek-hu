<?php

namespace Framework\Database\Repository\Events;

use Framework\Event\Event;

abstract class BaseRepositoryEvent extends Event
{
    public $model;

    public function __construct($model)
    {
        $this->model = $model;
    }
}
