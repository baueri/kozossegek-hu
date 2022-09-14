<?php

namespace App\Admin\Controllers\Api;

use App\Admin\Controllers\AdminController;
use App\Enums\CityStatOrderColumn;
use App\Models\CityStat;
use App\Repositories\CityStatistics;
use Exception;

class ExportStatisticsController extends AdminController
{
    public function __invoke(CityStatistics $query)
    {
        $stream = fopen('php://output', 'w');
        try {
            $filename = 'export_' . now()->toDateString() . '.csv';

            fputcsv($stream, ['Város', 'Keresések', 'Megtekintett közösségek', 'Kapcsolatfelvételek']);

            $query->selectSums()->orderBySum(CityStatOrderColumn::search_count)->each(function (CityStat $row) use($stream) {
                fputcsv($stream, $row->getAttributes());
            });

            header('Content-Type: application/csv');
            header('Content-disposition: filename="' . $filename. '"');
            fclose($stream);
        } catch (Exception $e) {
            ob_clean();
            report($e);
        }
    }
}