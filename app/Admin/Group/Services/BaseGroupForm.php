<?php

namespace App\Admin\Group\Services;

use App\Enums\AgeGroup;
use App\Enums\GroupStatus;
use App\Enums\JoinMode;
use App\Enums\OccasionFrequency;
use App\Enums\Tag;
use App\Models\ChurchGroupView;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\Institutes;
use App\QueryBuilders\Users;
use Framework\Http\Request;

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
        $institute = $this->institutes->findOrNew($group->institute_id);
        $statuses = GroupStatus::mapTranslated();
        $occasion_frequencies = OccasionFrequency::cases();
        $age_groups = AgeGroup::cases();
        $action = $this->getAction($group);
        $spiritual_movements = db()->select('select * from spiritual_movements order by name');
        $tags = Tag::collect();
        $age_group_array = $group->getAgeGroups();
        $group_tags = $group->tags->pluck('tag')->all();
        $group_days = $group->getDays();
        $title = $group->exists() ? 'Közösség módosítása' : 'Új közösség létrehozása';
        $owner = $this->users->find($group->user_id);
        $join_modes = JoinMode::cases();
        $comment = $group?->comment;
        $lastCommenter = $comment?->lastCommenter->name;

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
            'join_modes',
            'comment',
            'lastCommenter'
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
