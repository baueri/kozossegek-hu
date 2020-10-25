<?php

namespace App\Models;

/**
 * Description of GroupView
 *
 * @author ivan
 */
class GroupView extends Group
{
    public $institute_name;

    public $leader_name;

    public $spiritual_movement;

    public $city;

    public $district;
    
    /**
     * @return string
     */
    public function url(): string
    {
        $intezmeny = \Framework\Support\StringHelper::slugify($this->institute_name);
        $varos = \Framework\Support\StringHelper::slugify($this->city);
        return route('kozosseg', ['varos' => $varos, 'intezmeny' => $intezmeny, 'kozosseg' => $this->slug()]);
    }
}
