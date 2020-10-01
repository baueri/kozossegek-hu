<?php

namespace App\Models;

use Framework\Model\Model;

/**
 * Description of City
 *
 * @author ivan
 */
class City extends Model
{
    public $id;

    public $name;

    public $county_id;

    public $country_code;

    public $county;
}
