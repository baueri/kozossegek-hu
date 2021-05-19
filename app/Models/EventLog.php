<?php

namespace App\Models;

use Framework\Model\Model;

class EventLog extends Model
{
    public $id;

    public $type;

    public $log;

    public $user_id;

    public $created_at;
}
