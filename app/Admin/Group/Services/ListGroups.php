<?php

namespace App\Admin\Group\Services;

use App\Admin\Group\GroupTable;
use App\Repositories\AgeGroupRepository;
use App\Repositories\CityRepository;
use App\Repositories\GroupStatusRepository;
use App\Repositories\InstituteRepository;
use App\Repositories\OccasionFrequencyRepository;
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
     * @var InstituteRepository
     */
    private $instituteRepository;

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
     * @param InstituteRepository $instituteRepository
     * @param CityRepository $cityRepository
     */
    public function __construct(Request $request, GroupTable $table, AgeGroupRepository $ageGroupRepository, OccasionFrequencyRepository $occasionFrequencyRepository,
                                InstituteRepository $instituteRepository, CityRepository $cityRepository)
    {
        $this->table = $table;
        $this->ageGroupRepository = $ageGroupRepository;
        $this->occasionFrequencyRepository = $occasionFrequencyRepository;
        $this->instituteRepository = $instituteRepository;
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
        $institute = $this->instituteRepository->find($this->request['institute_id']);
        $filter = $this->request;
        $table = $this->table;

        return view('admin.group.list', compact('table', 'age_groups', 'occasion_frequencies', 'statuses',
            'institute', 'filter'));
    }

}
