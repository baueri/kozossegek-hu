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
        $page = $request['pg'] ?: 1;
        
        $groups = $service->search(collect($request->all()), $page);
        
        $model = [
            'groups' => $groups,
            'occasion_frequencies' => $occasionFrequencyRepository->all(),
            'age_groups' => $ageGroupRepository->all(),
            'page' => $page,
            'total' => $groups['total'],
            'perpage' => $groups['perpage']
        ];
        
        return $this->view('portal.kozossegek', $model);
    }

}
