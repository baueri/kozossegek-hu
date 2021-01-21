<?php

namespace App\Models;

use Framework\Support\StringHelper;

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
        $intezmeny = StringHelper::slugify($this->institute_name);
        $varos = StringHelper::slugify($this->city);
        return route('kozosseg', ['varos' => $varos, 'intezmeny' => $intezmeny, 'kozosseg' => $this->slug()]);
    }
}
