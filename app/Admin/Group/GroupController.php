<?php


namespace App\Admin\Group;


use App\Admin\Controllers\AdminController;
use App\Repositories\AgeGroupRepository;
use App\Repositories\OccasionFrequencyRepository;

class GroupController extends AdminController
{
    public function list(GroupTable $table, AgeGroupRepository $ageGroupRepository, OccasionFrequencyRepository $occasionFrequencyRepository)
    {
        $age_groups = $ageGroupRepository->all();
        $occasion_frequencies = $occasionFrequencyRepository->all();
        return $this->view('admin.group.list', compact('table', 'age_groups', 'occasion_frequencies'));
    }

    public function create()
    {
        return $this->view('admin.group.create');
    }
}