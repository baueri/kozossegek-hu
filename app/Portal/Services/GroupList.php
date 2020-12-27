<?php

namespace App\Portal\Services;

use App\Enums\GroupStatusEnum;
use App\Repositories\AgeGroups;
use App\Repositories\OccasionFrequencies;
use Framework\Http\Request;
use ReflectionException;

/**
 * Description of GroupList
 *
 * @author ivan
 */
class GroupList
{

    /**
     * @var SearchGroupService
     */
    private SearchGroupService $service;

    /**
     * @var Request
     */
    private Request $request;

    /**
     * @var AgeGroups
     */
    private AgeGroups $ageGroups;

    /**
     * @var OccasionFrequencies
     */
    private OccasionFrequencies $occasionFrequencies;

    public function __construct(
        Request $request,
        SearchGroupService $service,
        OccasionFrequencies $occasionFrequencies,
        AgeGroups $ageGroups
    ) {
        $this->occasionFrequencies = $occasionFrequencies;
        $this->ageGroups = $ageGroups;
        $this->request = $request;
        $this->service = $service;
    }

    /**
     * @return string
     * @throws ReflectionException
     */
    public function getHtml()
    {
        $filter = $this->request->only('varos', 'search', 'korosztaly', 'rendszeresseg', 'tags');
        $filter['order_by'] = ['city', 'district'];
        $filter['pending'] = 0;
        $filter['status'] = GroupStatusEnum::ACTIVE;
        $groups = $this->service->search($filter, 18);

        if ($groupIds = $groups->pluck('id')->all()) {
            $group_tags = builder('v_group_tags')->whereIn('group_id', $groupIds)->get();
            $groups->withMany($group_tags, 'tags', 'id', 'group_id');
        }

        $template = $this->request['view'] ?: 'grid2';
        $model = [
            'groups' => $groups,
            'occasion_frequencies' => $this->occasionFrequencies->all(),
            'age_groups' => $this->ageGroups->all(),
            'page' => $groups->page(),
            'total' => $groups->total(),
            'perpage' => $groups->perpage(),
            'filter' => $filter,
            'selected_tags' => explode(',', $filter['tags']),
            'tags' => builder('tags')->get(),
            'template' => $template
        ];

        return view('portal.kozossegek', $model);
    }
}
