<?php

namespace App\Repositories;

/**
 * Description of GroupStatusRepository
 *
 * @author ivan
 */
class GroupStatusRepository {
        
    /**
     * 
     * @return Denomination[]
     */
    public function all() : array
    {
        $values = \App\Enums\GroupStatusEnum::values();
        return array_map(function($value){
            return new \App\Models\GroupStatus($value);
        }, $values);
    }
}
