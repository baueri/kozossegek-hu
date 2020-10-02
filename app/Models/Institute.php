<?php

namespace App\Models;

/**
 * Description of Institute
 *
 * @author ivan
 */
class Institute extends \Framework\Model\Model
{
    use \Framework\Model\TimeStamps;

    public $id;

    public $name;

    public $city;

    public $district;

    public $address;

    public $leader_name;

    public $user_id;

    public $user;
}
