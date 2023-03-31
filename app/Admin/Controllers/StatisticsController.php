<?php

namespace App\Admin\Controllers;

use App\Admin\Components\AdminTable\PaginatedAdminTable;
use App\Repositories\CityStatistics;
use Framework\Database\Builder;
use Framework\Database\PaginatedResultSetInterface;
use Framework\Http\Request;

class StatisticsController extends AdminController
{
    public function index(Request $request): string
    {
        return view('admin.statistics.index', ['table' => $this->statisticsTable(), 'varos' => $request['varos'] ?? '', 'periodus' => $request['periodus']]);
    }

    private function statisticsTable(): PaginatedAdminTable
    {
        return new class(request()) extends PaginatedAdminTable
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

    public function keywords(): string
    {
        $popularKeywords = Builder::query()
            ->from('stat_city_keywords')
            ->select(['keyword', 'sum(cnt) as cnt'])
            ->groupBy('keyword')
            ->orderBy('sum(cnt) desc')
            ->having('sum(cnt) >= 10')
            ->collect()
            ->map(function (array $row) {
                return "{$row['keyword']} <span class='text-danger'>({$row['cnt']})</span>";
            })->implode(', ');

        return view('admin/statistics/keywords', ['table' => $this->keywordsTable(), 'popularKeywords' => $popularKeywords]);
    }

    private function keywordsTable(): PaginatedAdminTable
    {
        return new class($this->request) extends PaginatedAdminTable
        {
            protected array $columns = [
                'city' => 'Város',
                'keyword' => 'Keresőszavak',
                'tag' => 'Címkék',
                'age_group' => 'Korosztályok'
            ];

            protected array $columnClasses = [
                'keyword' => 'w-50'
            ];

            /**
             * @return \Framework\Database\PaginatedResultSetInterface
             */
            protected function getData(): PaginatedResultSetInterface
            {
                db()->execute('SET SESSION group_concat_max_len = 1000000;');
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

            private function concatField(string $fieldName): string
            {
                return <<<STRING
                    GROUP_CONCAT(case when type="{$fieldName}" then concat(keyword, " (", cnt, ")") end ORDER BY cnt DESC SEPARATOR ", ") as {$fieldName}
                STRING;
            }

            public function getKeyword($keyword): string
            {
                return $this->setColor($keyword);
            }

            public function getTag($keyword): string
            {
                return $this->setColor($keyword);
            }

            public function getAgeGroup($keyword): string
            {
                return $this->setColor($keyword);
            }

            private function setColor($keyword): string
            {
                return preg_replace('/(\(\d+\))/', '<span class="text-danger">$1</span>', $keyword ?? '');
            }
        };
    }
}
