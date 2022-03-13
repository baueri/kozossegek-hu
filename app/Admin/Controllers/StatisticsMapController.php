<?php

namespace App\Admin\Controllers;

use App\Repositories\CityStatistics;

class StatisticsMapController extends AdminController
{
    public function __invoke(): string
    {
        return view('admin.statistics.map', ['interaction_weight' => CityStatistics::INTERACTION_MIN_WEIGHT]);
    }
}
