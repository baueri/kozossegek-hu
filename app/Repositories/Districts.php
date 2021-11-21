<?php

namespace App\Repositories;

use Framework\Support\Collection;

class Districts
{
    public function searchDistrict(?string $keyword, ?string $city): Collection
    {
        $rows = builder()->from('institutes')
            ->select('DISTINCT district')
            ->where('district', 'like', "%$keyword%")
            ->where('city', $city)
            ->limit(15)
            ->get();

        return collect($rows)->pluck('district');
    }
}
