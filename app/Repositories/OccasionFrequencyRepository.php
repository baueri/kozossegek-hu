<?php

namespace App\Repositories;

/**
 * Description of OccasionFrequencyRepository
 *
 * @author ivan
 */
class OccasionFrequencyRepository {
    
    /**
     * 
     * @return \App\Models\OccasionFrequency[]
     */
    public function all()
    {
        $values = \App\Enums\OccasionFrequencyEnum::values();
        return array_map(function($value){
            return new \App\Models\OccasionFrequency($value);
        }, $values);
    }
}
