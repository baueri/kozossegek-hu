<?php

namespace App\Admin\Group\Services;

use App\Enums\JoinMode;
use App\Models\EntityGroupView;
use App\Repositories\AgeGroups;
use App\Repositories\GroupStatusRepository;
use App\Repositories\OccasionFrequencies;
use Framework\Http\Request;
use App\Repositories\Groups;
use App\Repositories\Institutes;
use App\Repositories\Users;
use App\Models\Institute;
use App\Enums\DayEnum;

class BaseGroupForm
{

    protected Institutes $institutes;

    protected Groups $repository;

    protected Users $users;

    protected Request $request;

    public function __construct(
        Request $request,
        Institutes $institutes,
        Users $users
    ) {
        $this->request = $request;
        $this->institutes = $institutes;
        $this->users = $users;
    }

    protected function getFormData(EntityGroupView $group): array
    {
        $institute = $this->institutes->find($group->institute_id) ?: new Institute();
        $statuses = (new GroupStatusRepository())->all();
        $occasion_frequencies = (new OccasionFrequencies())->all();
        $age_groups = (new AgeGroups())->all();
        $action = $this->getAction($group);
        $spiritual_movements = db()->select('select * from spiritual_movements order by name');
        $tags = builder('tags')->select('*')->get();
        $age_group_array = is_array($group->age_group) ? $group->age_group : array_filter(explode(',', $group->age_group));
        $group_tags = collect(builder('v_group_tags')
            ->apply('whereGroupId', $group->id)->get())
            ->pluck('tag')->all();
        $days = DayEnum::asArray();
        $group_days = explode(',', $group->on_days);
        $title = $group->exists() ? 'Közösség módosítása' : 'Új közösség létrehozása';
        $owner = $this->users->find($group->user_id);
        $join_modes = JoinMode::getModesWithName();

        return compact(
            'group',
            'institute',
            'statuses',
            'occasion_frequencies',
            'age_groups',
            'action',
            'spiritual_movements',
            'tags',
            'age_group_array',
            'group_tags',
            'days',
            'group_days',
            'title',
            'owner',
            'join_modes'
        );
    }

    public function render(EntityGroupView $group): string
    {
        return view('admin.group.form', $this->getFormData($group));
    }

    protected function getAction($group): string
    {
        return route('admin.group.do_create');
    }
}
