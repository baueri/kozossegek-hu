<?php

namespace App\Repositories;

use App\Models\Institute;
use Framework\Database\PaginatedResultSet;
use Framework\Model\ModelCollection;
use Framework\Model\PaginatedModelCollection;
use Framework\Repository;

/**
 * @author ivan
 * @extends Repository<\App\Models\Institute>
 */
class Institutes extends Repository
{
    /**
     *
     * @param string|null $keyword
     * @param string|null $city
     * @return PaginatedResultSet|Institute[]
     */
    public function search(?string $keyword = null, ?string $city = null)
    {
        $builder =  $this->getBuilder()->apply(['notDeleted', 'approved']);
        if ($city) {
            $builder->where('city', $city);
        }

        if ($keyword) {
            $keyword = trim($keyword, ' -*()');
            $builder->whereRaw(
                'MATCH (name, name2, city, district) AGAINST (? IN BOOLEAN MODE)',
                [$keyword ? '+' . str_replace(' ', '* +', $keyword) . '*' : '']
            );
        }

        $rows = $builder->orderBy('name', 'asc')->paginate(15);

        return $this->getInstances($rows);
    }

    public function getInstitutes()
    {
        return $this->getInstances($this->getBuilder()->paginate(30));
    }

    public function getInstitutesForAdmin($filter = []): PaginatedModelCollection
    {
        $builder = $this->getBuilder()->whereNull('institutes.deleted_at');

        if ($city = $filter['city']) {
            $builder->addSelect('institutes.*')->where('city', $city);
        }

        if ($name = $filter['search']) {
            $matchAgainst = trim($name, ' -*()');
            $builder->whereRaw(
                'MATCH (name, name2, city, district) AGAINST (? IN BOOLEAN MODE)',
                [$matchAgainst ? '+' . str_replace(' ', '* +', $matchAgainst) . '*' : '']
            );
            $builder->whereRaw("(institutes.name like ? or institutes.name2 like ?)", ["%$name%", "%$name%"]);
        }

        if ($filter['sort']) {
            $orderBy = $filter['order_by'] ?: self::getPrimaryCol();
            if ($orderBy != 'group_count') {
                $builder->orderBy($orderBy, $filter['sort']);
            }
        } else {
            $builder->orderBy('institutes.id', 'desc');
        }

        return $this->getInstances($builder->paginate(30));
    }


    //put your code here
    public static function getModelClass(): string
    {
        return Institute::class;
    }

    public static function getTable(): string
    {
        return 'institutes';
    }

    public function searchByCityAndInstituteName(string $city, string $institute): ?Institute
    {
        $builder = $this->getBuilder()
            ->whereRaw("MATCH(city) AGAINST(? IN BOOLEAN MODE)", [$city])
            ->whereRaw("MATCH(name) AGAINST(? IN BOOLEAN MODE)", [$institute]);

        $row = $builder->first();

        return $this->getInstance($row);
    }
}
