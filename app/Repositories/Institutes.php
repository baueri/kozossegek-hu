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
     * @param null $city
     * @return PaginatedResultSet|Institute[]
     */
    public function search($keyword, $city = null)
    {
        $builder =  $this->getBuilder()->apply(['notDeleted', 'approved']);

        if ($city) {
            $builder->where('city', $city);
        }

        if ($keyword) {
            $keyword = trim($keyword, ' ');
            $builder->whereRaw('MATCH (name, city, district) AGAINST (? IN BOOLEAN MODE)', [$keyword ? '+' . str_replace(' ', '* +', $keyword) . '*' : '']);
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
        $builder = $this->getBuilder()->whereNull('deleted_at');

        if ($city = $filter['city']) {
            $builder->where('city', $city);
        }

        if ($name = $filter['search']) {
            $builder->where('name', 'like', "%$name%");
        }

        if ($filter['sort']) {
            $orderBy = $filter['order_by'] ?: self::getPrimaryCol();
            if ($orderBy == 'group_count') {

            } else {
                $builder->orderBy($orderBy, $filter['sort']);
            }
        } else {
            $builder->orderBy('id', 'desc');
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
