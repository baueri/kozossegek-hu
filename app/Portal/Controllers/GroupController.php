<?php

namespace App\Portal\Controllers;
use App\Portal\Services\SearchGroupService;
use App\Repositories\AgeGroupRepository;
use App\Repositories\InstituteRepository;
use App\Repositories\GroupViewRepository;
use App\Repositories\OccasionFrequencyRepository;
use Framework\Http\Controller;
use Framework\Http\Request;
use Framework\Model\ModelNotFoundException;
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

        return view('portal.kozossegek', $model);
    }

    public function kozosseg(Request $request, GroupViewRepository $repo, InstituteRepository $instituteRepo)
    {
        $backUrl = null;

        if (strpos($_SERVER['HTTP_REFERER'], route('portal.groups')) !== false) {
            $backUrl = $_SERVER['HTTP_REFERER'];
        }

        $slug = $request['kozosseg'];
        $group = $repo->findBySlug($slug);

        if (!$group) {
            throw new ModelNotFoundException();
        }

        $institute = $instituteRepo->find($group->institute_id);

        return view('portal.kozosseg', compact('group', 'institute', 'backUrl'));
    }

}
