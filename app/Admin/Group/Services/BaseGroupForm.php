<?php

namespace App\Admin\Group\Services;

use Framework\Http\Request;
use App\Repositories\GroupRepository;
use App\Repositories\InstituteRepository;
use App\Models\Group;

/**
 * Description of BaseGroupForm
 *
 * @author ivan
 */
class BaseGroupForm {
    
    /**
     * @var InstituteRepository
     */
    protected $instituteRepository;

    /**
     * @var GroupRepository
     */
    protected $repository;

    /**
     * @var Request
     */
    protected $request;

    /**
     * 
     * @param Request $request
     * @param GroupRepository $repository
     * @param InstituteRepository $instituteRepository
     */
    public function __construct(Request $request, GroupRepository $repository,
            InstituteRepository $instituteRepository) {
        $this->request = $request;
        $this->repository = $repository;
        $this->instituteRepository = $instituteRepository;
    }

    public function show() {
        $group = $this->getGroup();
        $institute = $this->instituteRepository->find($group->institute_id);
        $denominations = (new \App\Repositories\DenominationRepository)->all();
        $statuses = (new \App\Repositories\GroupStatusRepository)->all();
        $occasion_frequencies = (new \App\Repositories\OccasionFrequencyRepository)->all();
        $age_groups = (new \App\Repositories\AgeGroupRepository)->all();
        $action = $this->getAction($group);

        return view('admin.group.edit', compact('group', 'institute', 'denominations',
                'statuses', 'occasion_frequencies', 'age_groups', 'action'));
    }
    
    protected function getGroup(): Group
    {
        return new Group;
    }
    
    protected function getAction(Group $group)
    {
        return route('admin.group.do_create');
    }
}
