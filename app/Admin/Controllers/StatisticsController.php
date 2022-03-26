<?php

namespace App\Admin\Controllers;

use App\Admin\Components\AdminTable\AdminTable;
use App\Models\City;
use App\Models\CityStat;
use App\Repositories\CityStatistics;
use Framework\Database\Builder;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Http\Request;
use Framework\Http\View\View;
use Framework\Model\ModelCollection;

class StatisticsController extends AdminController
{
    public function index(Request $request): string
    {
        return view('admin.statistics.index', ['table' => $this->statisticsTable(), 'varos' => $request['varos'] ?? '', 'periodus' => $request['periodus']]);
    }

    private function statisticsTable(): AdminTable
    {
        return new class(request()) extends AdminTable
        {
            protected array $columns = [
                'city' => 'Város',
                'search_count' => '<i class="fa fa-search" title="Keresések"></i>',
                'opened_groups_count' => '<i class="fa fa-eye" title="Megtekintett közösségek"></i>',
                'contacted_groups_count' => '<i class="fa fa-envelope" title="Kapcsolatfelvételek"></i>'
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

            public function getCity($city, $row): string
            {
                $route = route('admin.group.list', ['varos' => $row->city]);
                return "<a href='{$route}'>{$city}</a>";
            }
        };
    }

    public function keywords()
    {
        return view('admin/statistics/keywords', ['table' => $this->keywordsTable()]);
    }

    private function keywordsTable(): AdminTable
    {
        return new class($this->request) extends AdminTable
        {
            protected array $columns = [
                'city' => 'Város',
                'keyword' => 'Keresőszavak',
                'age_group' => 'Korosztályok',
                'tag' => 'Kulcsszavak'
            ];

            protected array $columnClasses = [
                'keyword' => 'w-25',
                'age_group' => 'w-25',
            ];

            /**
             * @return \Framework\Database\PaginatedResultSetInterface
             */
            protected function getData(): PaginatedResultSetInterface
            {
                return Builder::query()
                    ->from('stat_city_keywords')
                    ->select([
                        'city',
                        $this->concatField('keyword'),
                        $this->concatField('age_group'),
                        $this->concatField('tag')
                    ])
                    ->where('city', '<>', '')
                    ->groupBy('city')
                    ->orderBy('sum(cnt)', 'desc')
                    ->paginate();
            }

            private function concatField(string $fieldName)
            {
                return <<<STRING
                    GROUP_CONCAT(case when type="{$fieldName}" then concat(keyword, " <span class='text-danger'>(", cnt, ")</span>") end SEPARATOR ", ") as {$fieldName}
                STRING;
            }
        };
    }
}
