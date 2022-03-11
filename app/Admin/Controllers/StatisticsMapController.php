<?php

namespace App\Admin\Controllers;

class StatisticsMapController extends AdminController
{
    public function __invoke(): string
    {
        return view('admin.statistics.map');
    }
}
