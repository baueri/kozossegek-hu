<?php

namespace App\Repositories;

use App\Models\AgeGroup;

class AgeGroupRepository {
    
    /**
     * 
     * @return AgeGroup[]
     */
    public function all() : array
    {
        $values = \App\Enums\AgeGroupEnum::values();
        return array_map(function($value){
            return new AgeGroup($value);
        }, $values);
    }
}
