<?php

namespace App\Portal\Controllers;
use App\Portal\Services\SearchGroupService;
use App\Repositories\AgeGroupRepository;
use App\Repositories\GroupRepository;
use App\Repositories\InstituteRepository;
use App\Repositories\OccasionFrequencyRepository;
use Framework\Http\Controller;
use Framework\Http\Request;
/**
 * Description of GroupController
 *
 * @author ivan
 */
class GroupController extends Controller {

    public function kozossegek(Request $request, SearchGroupService $service,
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
    
    public function kozosseg(Request $request, GroupRepository $repo, InstituteRepository $instituteRepo)
    {
        $backUrl = null;

        if (strpos($_SERVER['HTTP_REFERER'], route('portal.groups')) !== false) {
            $backUrl = $_SERVER['HTTP_REFERER'];
        }

        $slug = $request->getUriValue('kozosseg');
        $group = $repo->findBySlug($slug);

        $institute = $instituteRepo->find($group->institute_id);
        
        return $this->view('portal.kozosseg', compact('group', 'institute', 'backUrl'));
    }

}
