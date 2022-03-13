<?php

namespace App\Admin\Controllers;

use App\Admin\Components\AdminTable\AdminTable;
use App\Repositories\CityStatistics;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Http\Request;

class StatisticsController extends AdminController
{
    public function __invoke(Request $request): string
    {
        return view('admin.statistics.index', ['table' => $this->table(), 'varos' => $request['varos'] ?? '', 'periodus' => $request['periodus']]);
    }

    private function table(): AdminTable
    {
        return new class(request()) extends AdminTable
        {
            protected array $columns = [
                'city' => 'Város',
                'search_count' => 'Keresések',
                'opened_groups_count' => 'Megtekintett közösségek',
                'contacted_groups_count' => 'Kapcsolatfelvételek'
            ];

            protected string $defaultOrderColumn = 'search_count';

            protected array $sortableColumns = ['city', 'search_count', 'opened_groups_count', 'contacted_groups_count'];

            protected function getData(): PaginatedResultSetInterface
            {
                return CityStatistics::query()
                    ->when(request()->get('varos'), fn ($query, $city) => $query->where('lower(city)', 'like', '%' . mb_strtolower($city) . '%'))
                    ->onPeriod(request()->get('periodus'))
                    ->selectSums()
                    ->orderBy("sum({$this->order[0]})", $this->order[1])
                    ->paginate();
            }

            public function getCity($city, $row)
            {
                $route = route('admin.group.list', ['varos' => $row['city']]);
                return "<a href='{$route}'>{$city}</a>";
            }
        };
    }
}
