<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Portal\Services;

use App\Enums\GroupStatusEnum;
use App\Repositories\AgeGroups;
use App\Repositories\OccasionFrequencies;
use Framework\Http\Request;
use Swoole\Http\Request as Request2;

/**
 * Description of GroupList
 *
 * @author ivan
 */
class GroupList {

    /**
     * @var SearchGroupService
     */
    private $service;

    /**
     * @var Request2
     */
    private $request;

    /**
     * @var AgeGroups
     */
    private $ageGroups;

    /**
     * @var OccasionFrequencies
     */
    private $occasionFrequencies;

    public function __construct(Request $request, SearchGroupService $service,
            OccasionFrequencies $occasionFrequencies, AgeGroups $ageGroups) {
        $this->occasionFrequencies = $occasionFrequencies;
        $this->ageGroups = $ageGroups;
        $this->request = $request;
        $this->service = $service;
    }
    
    public function getHtml()
    {
        $filter = $this->request->only('varos', 'search', 'korosztaly', 'rendszeresseg', 'tags');
        $filter['order_by'] = ['city', 'district'];
        $filter['pending'] = 0;
        $filter['status'] = GroupStatusEnum::ACTIVE;
        $groups = $this->service->search($filter);

        if (!$filter['varos']) {
            $groupsGrouped = $groups->groupBy('city');
        } else {
            $groupsGrouped = $groups->groupBy('district');
        }
        
        $model = [
            'groups' => $groups,
            'occasion_frequencies' => $this->occasionFrequencies->all(),
            'age_groups' => $this->ageGroups->all(),
            'page' => $groups->page(),
            'total' => $groups->total(),
            'perpage' => $groups->perpage(),
            'filter' => $filter,
            'selected_tags' => explode(',', $filter['tags']),
            'tags' => builder('tags')->get()
        ];

        return view('portal.kozossegek', $model);
    }
}
