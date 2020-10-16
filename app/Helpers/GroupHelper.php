<?php

namespace App\Helpers;

use App\Models\AgeGroup;
use Framework\Support\Collection;

class GroupHelper
{
    /**
     * 
     * @param string $ageGroup
     * @return string
     */
    public static function parseAgeGroup($ageGroup)
    {
        return static::getAgeGroups($ageGroup)->implode(', ');
    }
    
    /**
     * 
     * @param string $ageGroup
     * @return Collection
     */
    public static function getAgeGroups(string $ageGroup)
    {
        return (new Collection(explode(',', $ageGroup)))
            ->filter()
            ->make(AgeGroup::class)
            ->keyBy('name')
            ->map(function($ageGroup){
                return $ageGroup->translate();
            }, true);
    }
}
