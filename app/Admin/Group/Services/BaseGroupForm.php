<?php

namespace App\Admin\Group\Services;

use Framework\Http\Request;
use App\Repositories\Groups;
use App\Repositories\Institutes;
use App\Repositories\GroupViews;
use App\Models\Group;
use App\Models\Institute;
use App\Enums\DayEnum;

/**
 * Description of BaseGroupForm
 *
 * @author ivan
 */
class BaseGroupForm {

    /**
     * @var Institutes
     */
    protected $Institutes;

    /**
     * @var Groups
     */
    protected $repository;

    /**
     * @var Request
     */
    protected $request;

    /**
     *
     * @param Request $request
     * @param Groups $repository
     * @param Institutes $Institutes
     */
    public function __construct(Request $request, GroupViews $repository,
            Institutes $Institutes) {
        $this->request = $request;
        $this->repository = $repository;
        $this->Institutes = $Institutes;
    }

    public function show() {
        $group = $this->getGroup();
        $institute = $this->Institutes->find($group->institute_id) ?: new Institute;
        $denominations = (new \App\Repositories\Denominations)->all();
        $statuses = (new \App\Repositories\GroupStatusRepository)->all();
        $occasion_frequencies = (new \App\Repositories\OccasionFrequencies)->all();
        $age_groups = (new \App\Repositories\AgeGroups)->all();
        $action = $this->getAction($group);
        $spiritual_movements = db()->select('select * from spiritual_movements order by name');
        $tags = builder('tags')->select('*')->get();
        $age_group_array = array_filter(explode(',', $group->age_group));
        $group_tags = collect(builder('group_tags')->whereGroupId($group->id)->get())->pluck('tag')->all();
        $days = DayEnum::all();
        $group_days = explode(',', $group->on_days);
        $images = $group->getImages();
        
        
        return view('admin.group.create', compact('group', 'institute', 'denominations',
                'statuses', 'occasion_frequencies', 'age_groups', 'action', 'spiritual_movements', 'tags',
                'age_group_array', 'group_tags', 'days', 'group_days', 'images'));
    }

    protected function getGroup(): Group
    {
        return new Group;
    }

    protected function getAction(Group $group)
    {
        return route('admin.group.do_create');
    }
}
