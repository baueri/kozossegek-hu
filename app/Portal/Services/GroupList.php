<?php

namespace App\Portal\Services;

use App\Enums\GroupStatus;
use App\Enums\AgeGroup;
use Framework\Support\Collection;

class GroupList
{
    public function __construct(
        private SearchGroupService $service
    ) {
    }

    public function getHtml(Collection $request): string
    {
        $baseFilter = $request->only('search', 'korosztaly', 'tags', 'institute_id');
        $baseFilter['varos'] = $request['varos'];

        $filter = $baseFilter;

        $filter['pending'] = 0;
        $filter['status'] = GroupStatus::active;

        $statuses = GroupStatus::mapTranslated();

        $korosztaly = $filter['korosztaly'] ?? null;

        $model = [
            'age_groups' => AgeGroup::cases(),
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
