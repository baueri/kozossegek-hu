<?php

namespace Framework\Database\Repository\Events;

/**
 * Description of BaseRepositoryyEvent
 *
 * @author ivan
 */
abstract class BaseRepositoryEvent extends \Framework\Event\Event {

    /**
     * @var \Framework\Model\Model
     */
    public $model;

    /**
     * @param \Framework\Model\Model $model
     */
    public function __construct(\Framework\Model\Model $model) {
        $this->model = $model;
    }
}
