<?php

namespace App\Repositories;
use App\Models\Denomination;

/**
 * Description of DenominationRepository
 *
 * @author ivan
 */
class DenominationRepository {
    
    /**
     * 
     * @return Denomination[]
     */
    public function all() : array
    {
        $values = \App\Enums\DenominationEnum::values();
        return array_map(function($value){
            return new Denomination($value);
        }, $values);
    }
}
