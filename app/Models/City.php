<?php

namespace App\Models;

/**
 * Description of City
 *
 * @author ivan
 */
class City extends \Framework\Model\Model
{
    public $id;
    
    public $name;
    
    public $county_id;
    
    public $country_code;
    
    public $county;
}
