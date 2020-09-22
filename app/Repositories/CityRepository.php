<?php

namespace App\Repositories;

/**
 * Description of CityRepository
 *
 * @author ivan
 */
class CityRepository extends \Framework\Repository {
   
    /**
     * 
     * @param strgin $keyword
     * @param int $limit
     * @return ModelCollection
     */
    public function search($keyword, $limit = 10)
    {
        $rows = $this->getBuilder()
            ->where('name', 'like', "%$keyword%")
            ->limit($limit)
            ->orderBy('name', 'asc')
            ->get();
        
        $counties = builder()->select('*')->from('counties')->get();
        
        return $this->getInstances($rows)->with($counties, 'county', 'county_id');
    }
    
    public static function getModelClass(): string {
        return \App\Models\City::class;
    }

    public static function getTable(): string {
        return 'cities';
    }

}
