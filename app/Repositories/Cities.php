<?php

namespace App\Repositories;

/**
 * Description of Cities
 *
 * @author ivan
 */
class Cities extends \Framework\Repository {

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

    public function searchCitiesByExistingInstitutes($keyword, $limit = 10)
    {
        return builder('institutes')
            ->distinct()
            ->select('city')
            ->where('city', 'like', "%$keyword%")
            ->orderBy('name', 'asc')
            ->limit($limit)
            ->get();

    }

    public static function getModelClass(): string {
        return \App\Models\City::class;
    }

    public static function getTable(): string {
        return 'cities';
    }

}
