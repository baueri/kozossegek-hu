<?php

namespace App\Portal\Services;

use App\Enums\GroupStatus;
use App\Enums\AgeGroup;
use App\Portal\BreadCrumb\BreadCrumb;
use App\QueryBuilders\Cities;
use Framework\Support\Collection;
use function Symfony\Component\String\b;

class GroupList
{
    public function __construct(
        private SearchGroupService $service
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
            'tags' => builder('tags')->get(),
            'statuses' => $statuses,
            'selected_age_group' => $korosztaly,
            'header_background' => '/images/kozosseget_keresek.jpg',
            'age_group_query' => http_build_query($baseFilter),
            'breadcrumb' => $breadCrumb
        ];

        if (empty($baseFilter)) {
            return view('portal.kozossegek_no_filter', $model);
        }

        $groups = $this->service->search($filter, 18);

        $model = array_merge($model, [
            'groups' => $groups,
            'page' => $groups->page(),
            'total' => $groups->total(),
            'perpage' => $groups->perpage(),
        ]);

        return view('portal2.kozossegek', $model);
    }
}
