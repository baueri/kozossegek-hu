<?php

namespace App\Portal\Controllers;
use App\Repositories\AgeGroupRepository;
use App\Repositories\OccasionFrequencyRepository;
use Framework\Http\Request;
/**
 * Description of GroupController
 *
 * @author ivan
 */
class GroupController extends \Framework\Http\Controller {

    public function kozossegek(Request $request, \App\Portal\Services\SearchGroupService $service,
            OccasionFrequencyRepository $occasionFrequencyRepository, AgeGroupRepository $ageGroupRepository)
    {
        $filter = collect($request->all());
        
        $groups = $service->search($filter);
        
        $model = [
            'groups' => $groups,
            'occasion_frequencies' => $occasionFrequencyRepository->all(),
            'age_groups' => $ageGroupRepository->all(),
            'page' => $groups->page(),
            'total' => $groups->total(),
            'perpage' => $groups->perpage(),
            'filter' => $filter
        ];
        
        return $this->view('portal.kozossegek', $model);
    }
    
    public function kozosseg(Request $request, \App\Repositories\GroupRepository $repo, \App\Repositories\InstituteRepository $instituteRepo)
    {
        $slug = $request->getUriValue('kozosseg');
        
        $group = $repo->findBySlug($slug);
        $institute = $instituteRepo->find($group->institute_id);
        
        return $this->view('portal.kozosseg', compact('group', 'institute'));
    }

}
