<?php

namespace App\Admin\Controllers\Api;

use App\Admin\Controllers\AdminController;
use App\Enums\CityStatOrderColumn;
use App\Repositories\CityStatistics;

class ExportStatisticsController extends AdminController
{
    public function __invoke(CityStatistics $query)
    {
        $rows = $query->selectSums()->orderBySum(CityStatOrderColumn::search_count)->get();
        $stream = fopen('php://output', 'w');
        $filename = 'export_' . now()->toDateString() . '.csv';

        header('Content-Type: application/csv');
        header('Content-disposition: filename="' . $filename. '"');

        fputcsv($stream, ['Város', 'Keresések', 'Megtekintett közösségek', 'Kapcsolatfelvételek']);
        array_walk($rows, function ($row) use($stream) {
            fputcsv($stream, $row);
        });

        fclose($stream);
    }
}