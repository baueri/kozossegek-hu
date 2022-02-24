<?php

namespace App\Admin\Controllers;

use App\Admin\Components\AdminTable\AdminTable;
use Framework\Database\PaginatedResultSetInterface;

class StatisticsController extends AdminController
{
    public function __invoke(): string
    {
        return view('admin.statistics', ['table' => $this->table(), 'varos' => request()->get('varos')]);
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
                return builder('stat_city')
                    ->when(request()->get('varos'), fn ($query, $city) => $query->where('city', 'like', "%{$city}%"))
                    ->select('city, sum(search_count) as search_count, sum(opened_groups_count) as opened_groups_count, sum(contacted_groups_count) as contacted_groups_count')
                    ->groupBy('city')
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