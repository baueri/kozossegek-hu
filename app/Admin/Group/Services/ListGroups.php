<?php

namespace App\Admin\Group\Services;

use App\Admin\Group\GroupTable;
use App\Repositories\AgeGroupRepository;
use App\Repositories\OccasionFrequencyRepository;

/**
 * Description of GroupListService
 *
 * @author ivan
 */
class ListGroups {

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
     * @param GroupTable $table
     * @param AgeGroupRepository $ageGroupRepository
     * @param OccasionFrequencyRepository $occasionFrequencyRepository
     */
    public function __construct(GroupTable $table, AgeGroupRepository $ageGroupRepository, OccasionFrequencyRepository $occasionFrequencyRepository)
    {
        $this->table = $table;
        $this->ageGroupRepository = $ageGroupRepository;
        $this->occasionFrequencyRepository = $occasionFrequencyRepository;
    }

    /**
     * @return string
     */
    public function show()
    {
        $age_groups = $this->ageGroupRepository->all();
        $occasion_frequencies = $this->occasionFrequencyRepository->all();
        $table = $this->table;

        return view('admin.group.list', compact('table', 'age_groups', 'occasion_frequencies'));
    }

}
