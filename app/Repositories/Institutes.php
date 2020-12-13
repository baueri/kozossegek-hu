<?php

namespace App\Repositories;

use App\Models\Institute;
use Framework\Database\PaginatedResultSet;

/**
 * Description of Institutes
 *
 * @author ivan
 */
class Institutes extends \Framework\Repository
{
    /**
     *
     * @param string $keyword
     * @param string $city
     * @return PaginatedResultSet|Institute[]
     */
    public function search($keyword, $city = null)
    {
        $builder =  $this->getBuilder();

        if ($city) {
            $builder->where('city', $city);
        }
        
        if ($keyword) {
            $keyword = trim($keyword, ' ');
            $builder->whereRaw('MATCH (name, city) AGAINST (? IN BOOLEAN MODE)', [$keyword ? '+' . str_replace(' ', '* +', $keyword) . '*' : '']);
        }
        
        $rows = $builder->orderBy('name', 'asc')->paginate(15);

        return $this->getInstances($rows);
    }

    public function getInstitutes()
    {
        return $this->getInstances($this->getBuilder()->paginate(30));
    }
    
    public function getInstitutesForAdmin($filter = [])
    {
        $builder = $this->getBuilder()->orderBy('id', 'desc')->whereNull('deleted_at');

        if ($city = $filter['city']) {
            $builder->where('city', $city);
        }

        if ($name = $filter['search']) {
            $builder->where('name', 'like', "%$name%");
        }
        
        return $this->getInstances($builder->paginate(30));
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
