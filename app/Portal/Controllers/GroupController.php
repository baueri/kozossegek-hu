<?php

namespace App\Portal\Controllers;
use App\Portal\Services\SearchGroupService;
use App\Repositories\AgeGroups;
use App\Repositories\Institutes;
use App\Repositories\GroupViews;
use App\Repositories\OccasionFrequencies;
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
            OccasionFrequencies $OccasionFrequencies, AgeGroups $AgeGroups)
    {
        $filter = collect($request->all());

        $groups = $service->search($filter);

        $model = [
            'groups' => $groups,
            'occasion_frequencies' => $OccasionFrequencies->all(),
            'age_groups' => $AgeGroups->all(),
            'page' => $groups->page(),
            'total' => $groups->total(),
            'perpage' => $groups->perpage(),
            'filter' => $filter
        ];

        return view('portal.kozossegek', $model);
    }

    public function kozosseg(Request $request, GroupViews $repo, Institutes $instituteRepo)
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
        $tags = collect(explode(',', $group->tags))->filter();
        $tag_names = $tags->isNotEmpty() ? collect(builder('tags')->whereIn('slug', $tags->all())->get()) : [];

        return view('portal.kozosseg', compact('group', 'institute', 'backUrl', 'tag_names'));
    }

}
