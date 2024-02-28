<?php

namespace App\Portal\Services;

use App\Enums\AgeGroup;
use App\Enums\GroupStatus;
use App\Enums\Tag;
use App\Portal\BreadCrumb\BreadCrumb;
use App\Portal\Services\Search\SearchRepository;
use Framework\Support\Collection;

class GroupList
{
    public function __construct(
        protected readonly SearchRepository $repository
    ) {
    }

    public function getHtml(Collection $request, ?BreadCrumb $breadCrumb = null): string
    {
        $baseFilter = $request->only('search', 'korosztaly', 'tags', 'institute_id');
        $baseFilter['varos'] = $request['varos'];

        if (request()->route->getAs() === 'portal.groups.in_city') {
            preg_match('#' . request()->route->getUriMask() . '#', request()->uri, $matches);
            $baseFilter['varos'] = $matches[1] ?? null;
        }

        $filter = $baseFilter;

        $filter['pending'] = 0;
        $filter['status'] = GroupStatus::active->value();

        $statuses = GroupStatus::mapTranslated();

        $korosztaly = $filter['korosztaly'] ?? null;

        $model = [
            'age_groups' => AgeGroup::cases(),
            'filter' => collect($filter),
            'selected_tags' => array_filter(explode(',', $filter['tags'] ?? '')),
            'tags' => Tag::collect(),
            'statuses' => $statuses,
            'selected_age_group' => $korosztaly,
            'age_group_query' => http_build_query($baseFilter),
            'breadcrumb' => $breadCrumb
        ];

        $groups = $this->repository->search($filter, 18);

        $model = array_merge($model, [
            'groups' => $groups,
            'page' => $groups->page(),
            'total' => $groups->total(),
            'perpage' => $groups->perpage(),
        ]);

        return view('portal.kozossegek', $model);
    }
}
