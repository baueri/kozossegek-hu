<?php

namespace App\Repositories;

class Districts
{

    public function searchDistrict(?string $keyword, ?string $city)
    {
        $rows = builder()->table('institutes')
            ->select('DISTINCT district')
            ->where('district', 'like', "%$keyword%")
            ->where('city', $city)
            ->limit(15)
            ->get();

        return collect($rows)->pluck('district');
    }
}
