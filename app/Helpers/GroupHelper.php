<?php

namespace App\Helpers;

use App\Models\AgeGroup;

class GroupHelper
{
    public static function parseAgeGroup($ageGroup)
    {
        return collect(explode(',', $ageGroup))
            ->filter()
            ->make(AgeGroup::class)
            ->map(function($ageGroup){
                return $ageGroup->translate();
            })
            ->implode(', ');
    }
}
