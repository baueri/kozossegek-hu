<?php

namespace App\Admin\Group\Services;

use App\Enums\AgeGroup;
use App\Enums\JoinMode;
use App\Enums\OccasionFrequency;
use App\Models\ChurchGroupView;
use App\QueryBuilders\Users;
use App\Repositories\GroupStatusRepository;
use Framework\Http\Request;
use Legacy\Institute;
use Legacy\Institutes;

class BaseGroupForm
{
    public function __construct(
        protected Request $request,
        protected Institutes $institutes,
        protected Users $users
    ) {
    }

    protected function getFormData(ChurchGroupView $group): array
    {
        $institute = $this->institutes->find($group->institute_id) ?: new Institute();
        $statuses = (new GroupStatusRepository())->all();
        $occasion_frequencies = OccasionFrequency::cases();
        $age_groups = AgeGroup::cases();
        $action = $this->getAction($group);
        $spiritual_movements = db()->select('select * from spiritual_movements order by name');
        $tags = builder('tags')->get();
        $age_group_array = $group->getAgeGroups();
        $group_tags = collect(builder('v_group_tags')
            ->apply('whereGroupId', $group->id)->get())
            ->pluck('tag')->all();
        $group_days = $group->getDays();
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
            'group_days',
            'title',
            'owner',
            'join_modes'
        );
    }

    public function render(ChurchGroupView $group): string
    {
        return view('admin.group.form', $this->getFormData($group));
    }

    protected function getAction($group): string
    {
        return route('admin.group.do_create');
    }
}
