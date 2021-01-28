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

    private ?string $cachedUrl = null;

    /**
     * @return string
     */
    public function url(): string
    {
        if ($this->cachedUrl) {
            return $this->cachedUrl;
        }

        $intezmeny = StringHelper::slugify($this->institute_name);
        $varos = StringHelper::slugify($this->city);
        return $this->cachedUrl = route('kozosseg', ['varos' => $varos, 'intezmeny' => $intezmeny, 'kozosseg' => $this->slug()]);
    }
}
