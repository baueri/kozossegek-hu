<?php

namespace App\Admin\Group\Services;

use Framework\Http\Request;
use App\Repositories\Groups;
use App\Repositories\Institutes;
use App\Repositories\GroupViews;
use App\Repositories\Users;
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
    protected $institutes;

    /**
     * @var Groups
     */
    protected $repository;

   /**
    * @var Users
    */
    protected $users;

    /**
     * @var Request
     */
    protected $request;

    /**
     *
     * @param Request $request
     * @param Groups $repository
     * @param Institutes $institutes
     */
    public function __construct(Request $request, GroupViews $repository,
            Institutes $institutes, Users $users) {
        $this->request = $request;
        $this->repository = $repository;
        $this->institutes = $institutes;
        $this->users = $users;
    }

    public function show() {
        $group = $this->getGroup();
        $institute = $this->institutes->find($group->institute_id) ?: new Institute;
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
        $title = $group->exists() ? 'Közösség módosítása' : 'Új közösség létrehozása';
        $owner = $this->users->find($group->user_id);

        return view('admin.group.form', compact('group', 'institute', 'denominations',
                'statuses', 'occasion_frequencies', 'age_groups', 'action', 'spiritual_movements', 'tags',
                'age_group_array', 'group_tags', 'days', 'group_days', 'images', 'title', 'owner'));
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
