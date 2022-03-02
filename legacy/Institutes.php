<?php

namespace Legacy;

use Framework\Database\PaginatedResultSet;
use Framework\Model\PaginatedModelCollection;
use Framework\Repository;

/**
 * @extends Repository<\Legacy\Institute>
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
