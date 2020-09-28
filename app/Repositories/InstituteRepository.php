<?php

namespace App\Repositories;

use App\Models\Institute;

/**
 * Description of InstituteRepository
 *
 * @author ivan
 */
class InstituteRepository extends \Framework\Repository
{
    /**
     * 
     * @param type $keyword
     * @param type $city
     * @return \Framework\Database\PaginatedResultSet|Institute[]
     */
    public function search($keyword, $city)
    {
        $rows = $this->getBuilder()
                ->where('city', $city)
                ->where('name', 'like', "%$keyword%")
                ->paginate(15);
        
        return $this->getInstances($rows);
    }
    
    public function getInstitutes()
    {
        return $this->getInstances($this->getBuilder()->paginate(30));
    }
    
    
    //put your code here
    public static function getModelClass(): string {
        return Institute::class;
    }

    public static function getTable(): string {
        return 'institutes';
    }

    public function getInstitutesByIds($instituteIds)
    {
        if (empty($instituteIds)) {
            return $this->getInstances([]);
        }
        return $this->getInstances($this->getBuilder()->whereIn('id', $instituteIds)->get());
    }

}
