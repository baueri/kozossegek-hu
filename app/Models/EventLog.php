<?php

namespace App\Models;

/**
 * Description of EventLog
 *
 * @author ivan
 */
class EventLog extends \Framework\Model\Model
{
    public $id;
    
    public $type;
    
    public $data;
    
    public $user_id;
}
