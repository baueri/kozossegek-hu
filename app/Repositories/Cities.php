<?php

namespace App\Repositories;

use App\Models\City;
use Framework\Database\PaginatedResultSet;
use Framework\Model\Model;
use Framework\Model\ModelCollection;
use Framework\Model\PaginatedModelCollection;
use Framework\Repository;

/**
 * Description of Cities
 *
 * @author ivan
 */
class Cities extends Repository
{

    /**
     *
     * @param string $keyword
     * @param int $limit
     * @return PaginatedResultSet|Model[]|ModelCollection|PaginatedModelCollection
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

    public function searchCitiesByExistingInstitutes($keyword, $limit = 10)
    {
        return builder('institutes')
            ->distinct()
            ->select('city')
            ->where('city', 'like', "%$keyword%")
            ->orderBy('city', 'asc')
            ->limit($limit)
            ->get();
    }

    public static function getModelClass(): string
    {
        return City::class;
    }

    public static function getTable(): string
    {
        return 'cities';
    }
}
