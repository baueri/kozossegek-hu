<?php

namespace App\Repositories;

use App\Models\Institute;
use Framework\Support\Collection;

/**
 * Description of InstituteRepository
 *
 * @author ivan
 */
class InstituteRepository extends \Framework\Repository
{
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
