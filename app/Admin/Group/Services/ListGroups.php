<?php

namespace App\Admin\Group\Services;

use App\Admin\Group\GroupTable;
use App\Repositories\AgeGroups;
use App\Repositories\Cities;
use App\Repositories\GroupStatusRepository;
use App\Repositories\OccasionFrequencies;
use App\Repositories\Users;
use Framework\Http\Request;
use ReflectionException;

class ListGroups
{
    private OccasionFrequencies $occasionFrequencies;

    private AgeGroups $ageGroups;

    private GroupTable $table;

    private Request $request;

    /**
     * @param Request $request
     * @param GroupTable $table
     * @param AgeGroups $AgeGroups
     * @param OccasionFrequencies $OccasionFrequencies
     */
    public function __construct(
        Request $request,
        GroupTable $table,
        AgeGroups $AgeGroups,
        OccasionFrequencies $OccasionFrequencies
    ) {
        $this->table = $table;
        $this->ageGroups = $AgeGroups;
        $this->occasionFrequencies = $OccasionFrequencies;
        $this->request = $request;
    }

    public function show(): string
    {
        $age_groups = $this->ageGroups->all();

        $occasion_frequencies = $this->occasionFrequencies->all();
        $statuses = (new GroupStatusRepository())->all();

        $institute = null;

        if ($institute_id = $this->request['institute_id']) {
            $institute = db()->first("select name from institutes where id=?", [$institute_id])['name'];
        }

        $filter = $this->request;
        $table = $this->table;
        $current_page = $this->getCurrentPage();
        $karbantarto = null;
        if ($filter['user_id']) {
            $karbantarto = app()->make(Users::class)->find($filter['user_id'])->name;
        }

        if ($current_page === 'pending') {
            $filter['pending'] = 1;
        }

        $pending_groups = builder()->from('church_groups')->where('pending', 1)->apply('notDeleted')->count();

        return view('admin.group.list', compact(
            'table',
            'age_groups',
            'occasion_frequencies',
            'statuses',
            'institute',
            'filter',
            'current_page',
            'pending_groups',
            'karbantarto',
        ));
    }

    private function getCurrentPage()
    {
        if ($this->request->route->getAs() == 'admin.group.trash') {
            return 'trash';
        }

        if ($this->request->route->getAs() == 'admin.group.list.pending') {
            return 'pending';
        }

        return $this->request['status'] ?: 'all';
    }
}
