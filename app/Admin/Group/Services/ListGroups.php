<?php

namespace App\Admin\Group\Services;

use App\Admin\Group\GroupTable;
use App\Enums\AgeGroup;
use App\Repositories\GroupStatusRepository;
use App\Repositories\OccasionFrequencies;
use App\Repositories\UsersLegacy;
use Framework\Http\Request;

class ListGroups
{
    public function __construct(
        private Request $request,
        private GroupTable $table,
        private OccasionFrequencies $occasionFrequencies
    ) {
    }

    public function show(): string
    {
        $age_groups = AgeGroup::cases();
        $occasion_frequencies = $this->occasionFrequencies->all();
        $statuses = (new GroupStatusRepository())->all();

        $institute = null;

        if ($institute_id = $this->request['institute_id']) {
            $institute = db()->first("select name from institutes where id=?", [$institute_id])['name'];
        }

        $filter = $this->request;
        $table = $this->table;
        $current_page = $this->getCurrentPage();
        $karbantarto = $filter['user_id'] ? app()->make(UsersLegacy::class)->find($filter['user_id'])->name : null;

        $filter['pending'] = $current_page === 'pending';

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
