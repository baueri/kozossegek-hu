<?php

namespace Legacy;

use App\Models\Traits\InstituteTrait;
use Framework\Model\Model;
use Framework\Model\TimeStamps;

class Institute extends Model
{
    use TimeStamps;
    use InstituteTrait;

    public $id;

    public $name;

    public $name2;

    public $city;

    public $district;

    public $address;

    public $leader_name;

    public $user_id;

    public $user;

    public $approved;

    public $miserend_id;

    public $image_url;

    public $website;
}
