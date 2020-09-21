<?php

namespace App\Events;

/**
 * Description of BaseLogEvent
 *
 * @author ivan
 */
class BaseLogEvent extends \Framework\Event\Event
{

    /**
     * @var App\Models\User|null
     */
    public $user;
    
    /**
     *
     * @var array
     */
    public $data;
    
    /**
     *
     * @var string
     */
    public $logType;

    /**
     * 
     * @param string $logType
     * @param array $data
     * @param \App\Events\App\Models\User|null $user
     */
    public function __construct(string $logType, array $data = [], ?App\Models\User $user = null) {
        $this->logType = $logType;
        $this->data = $data;
        $this->user = $user;
    }
}
