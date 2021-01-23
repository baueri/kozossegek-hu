<?php

namespace App\Admin\Group\Services;

use App\Admin\Group\GroupTable;
use App\Repositories\AgeGroups;
use App\Repositories\Cities;
use App\Repositories\GroupStatusRepository;
use App\Repositories\OccasionFrequencies;
use App\Enums\GroupStatusEnum;
use Framework\Http\Request;
use ReflectionException;

/**
 * Description of GroupListService
 *
 * @author ivan
 */
class ListGroups
{
    /**
     * @var OccasionFrequencies
     */
    private OccasionFrequencies $OccasionFrequencies;

    /**
     * @var AgeGroups
     */
    private AgeGroups $AgeGroups;

    /**
     * @var GroupTable
     */
    private GroupTable $table;

    /**
     * @var Cities
     */
    private Cities $cities;

    /**
     * @var Request
     */
    private Request $request;

    /**
     * @param Request $request
     * @param GroupTable $table
     * @param AgeGroups $AgeGroups
     * @param OccasionFrequencies $OccasionFrequencies
     * @param Cities $Cities
     */
    public function __construct(
        Request $request,
        GroupTable $table,
        AgeGroups $AgeGroups,
        OccasionFrequencies $OccasionFrequencies,
        Cities $Cities
    ) {
        $this->table = $table;
        $this->AgeGroups = $AgeGroups;
        $this->OccasionFrequencies = $OccasionFrequencies;
        $this->cities = $Cities;
        $this->request = $request;
    }

    /**
     * @return string
     * @throws ReflectionException
     */
    public function show()
    {
        $age_groups = $this->AgeGroups->all();
        $occasion_frequencies = $this->OccasionFrequencies->all();
        $statuses = (new GroupStatusRepository())->all();

        $institute = null;

        if ($institute_id = $this->request['institute_id']) {
            $institute = db()->fetchColumn("select name from institutes where id=?", [$institute_id]);
        }

        $filter = $this->request;
        $table = $this->table;
        $current_page = $this->getCurrentPage();
        $pending_groups = builder()->from('church_groups')->where('pending', 1)->apply('notDeleted')->count();

        return view('admin.group.list', compact(
            'table',
            'age_groups',
            'occasion_frequencies',
            'statuses',
            'institute',
            'filter',
            'current_page',
            'pending_groups'
        ));
    }

    private function getCurrentPage()
    {
        if ($this->request->route->getAs() == 'admin.group.trash') {
            return 'trash';
        }

        if ($this->request['pending']) {
            return 'pending';
        }

        return $this->request['status'] ?: 'all';
    }
}
