<?php

namespace App\Admin\Group\Services;

use App\Admin\Group\GroupTable;
use App\Repositories\AgeGroupRepository;
use App\Repositories\CityRepository;
use App\Repositories\GroupStatusRepository;
use App\Repositories\OccasionFrequencyRepository;

use App\Enums\GroupStatusEnum;
use Framework\Http\Request;

/**
 * Description of GroupListService
 *
 * @author ivan
 */
class ListGroups
{

    /**
     * @var OccasionFrequencyRepository
     */
    private $occasionFrequencyRepository;

    /**
     * @var AgeGroupRepository
     */
    private $ageGroupRepository;

    /**
     * @var GroupTable
     */
    private $table;

    /**
     * @var CityRepository
     */
    private $cityRepository;

    /**
     * @var Request
     */
    private $request;

    /**
     * @param Request $request
     * @param GroupTable $table
     * @param AgeGroupRepository $ageGroupRepository
     * @param OccasionFrequencyRepository $occasionFrequencyRepository
     * @param CityRepository $cityRepository
     */
    public function __construct(Request $request, GroupTable $table, AgeGroupRepository $ageGroupRepository,
                    OccasionFrequencyRepository $occasionFrequencyRepository, CityRepository $cityRepository)
    {
        $this->table = $table;
        $this->ageGroupRepository = $ageGroupRepository;
        $this->occasionFrequencyRepository = $occasionFrequencyRepository;
        $this->cityRepository = $cityRepository;
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function show()
    {
        $age_groups = $this->ageGroupRepository->all();
        $occasion_frequencies = $this->occasionFrequencyRepository->all();
        $statuses = (new GroupStatusRepository())->all();

        if($institute_id = $this->request['institute_id']) {
            $institute = db()->fetchColumn("select name from institutes where id=?", [$institute_id]);
        }

        $filter = $this->request;
        $table = $this->table;
        $current_page = $this->getCurrentPage();
        $pending_groups = builder()->from('groups')->where('status', GroupStatusEnum::PENDING)->count();

        return view('admin.group.list', compact('table', 'age_groups', 'occasion_frequencies', 'statuses',
            'institute', 'filter', 'current_page', 'pending_groups'));
    }

    private function getCurrentPage()
    {
        if ($this->request->route->getAs() == 'admin.group.trash') {
            return 'trash';
        }

        return $this->request['status'] ?: 'all';

    }

}
