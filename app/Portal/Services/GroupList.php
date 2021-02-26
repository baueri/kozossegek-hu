<?php

namespace App\Portal\Services;

use App\Enums\GroupStatusEnum;
use App\Repositories\AgeGroups;
use App\Repositories\GroupStatusRepository;
use App\Repositories\OccasionFrequencies;
use Framework\Support\Collection;
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
     * @var AgeGroups
     */
    private AgeGroups $ageGroups;

    /**
     * @var OccasionFrequencies
     */
    private OccasionFrequencies $occasionFrequencies;

    public function __construct(
        SearchGroupService $service,
        OccasionFrequencies $occasionFrequencies,
        AgeGroups $ageGroups
    ) {
        $this->occasionFrequencies = $occasionFrequencies;
        $this->ageGroups = $ageGroups;
        $this->service = $service;
    }

    /**
     * @param Collection $request
     * @return string
     * @throws ReflectionException
     */
    public function getHtml(Collection $request)
    {
        $filter = $request->only('search', 'korosztaly', 'rendszeresseg', 'tags', 'institute_id');
        $filter['varos'] = $request['varos'];

//        $filter['order_by'] = ['city', 'district'];
//        $filter['sort'] = 'asc';
        $filter['pending'] = 0;
        $filter['status'] = GroupStatusEnum::ACTIVE;
        $groups = $this->service->search($filter, 18);
        $statuses = (new GroupStatusRepository())->all();

        if ($groupIds = $groups->pluck('id')->all()) {
            $group_tags = builder('v_group_tags')->whereIn('group_id', $groupIds)->get();
            $groups->withMany($group_tags, 'tags', 'id', 'group_id');
        }

        $model = [
            'groups' => $groups,
            'occasion_frequencies' => $this->occasionFrequencies->all(),
            'age_groups' => $this->ageGroups->all(),
            'page' => $groups->page(),
            'total' => $groups->total(),
            'perpage' => $groups->perpage(),
            'filter' => collect($filter),
            'selected_tags' => array_filter(explode(',', $filter['tags'] ?? '')),
            'tags' => builder('tags')->get(),
            'header_background' => '/images/kozosseget_keresek.jpg',
            'statuses' => $statuses,
        ];

        return view('portal.kozossegek', $model);
    }
}
