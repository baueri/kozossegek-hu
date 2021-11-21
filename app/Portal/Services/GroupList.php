<?php

namespace App\Portal\Services;

use App\Enums\GroupStatusEnum;
use App\Models\AgeGroup;
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

    public function getHtml(Collection $request): string
    {
        $baseFilter = $request->only('search', 'korosztaly', 'tags', 'institute_id');
        $baseFilter['varos'] = $request['varos'];

        $filter = $baseFilter;

        $filter['pending'] = 0;
        $filter['status'] = GroupStatusEnum::ACTIVE;

        $statuses = (new GroupStatusRepository())->all();

        $korosztaly = $filter['korosztaly'] ?? null;
        $ageGroups = collect($this->ageGroups->all());

        $model = [
            'occasion_frequencies' => $this->occasionFrequencies->all(),
            'age_groups' => $ageGroups->map(fn (AgeGroup $ageGroup) => [
                'value' => $ageGroup->name,
                'name' => $ageGroup->translate()
            ]),
            'filter' => collect($filter),
            'selected_tags' => array_filter(explode(',', $filter['tags'] ?? '')),
            'tags' => builder('tags')->get(),
            'statuses' => $statuses,
            'selected_age_group' => $korosztaly,
            'header_background' => '/images/kozosseget_keresek.jpg',
            'age_group_query' => http_build_query($baseFilter)
        ];

        if ($request->isEmpty()) {
            return view('portal.kozossegek_no_filter', $model);
        }

        $groups = $this->service->search($filter, 18);
        if ($groupIds = $groups->pluck('id')->all()) {
            $group_tags = builder('v_group_tags')->whereIn('group_id', $groupIds)->get();
            $groups->withMany($group_tags, 'tags', 'id', 'group_id');
        }

        $model = array_merge($model, [
            'groups' => $groups,
            'page' => $groups->page(),
            'total' => $groups->total(),
            'perpage' => $groups->perpage(),
        ]);

        return view('portal.kozossegek', $model);
    }
}
