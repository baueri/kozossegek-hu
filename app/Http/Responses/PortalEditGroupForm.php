<?php

namespace App\Http\Responses;

use App\Enums\AgeGroup;
use App\Enums\GroupStatus;
use App\Enums\OccasionFrequency;
use App\Enums\Tag;
use App\Enums\WeekDay;
use App\Models\ChurchGroupView;
use App\QueryBuilders\Institutes;

class PortalEditGroupForm
{
    private Institutes $institutes;

    public function __construct(Institutes $institutes)
    {
        $this->institutes = $institutes;
    }

    public function __invoke(ChurchGroupView $group): string
    {
        $statuses = GroupStatus::mapTranslated();
        $tags = Tag::collect();
        $occasion_frequencies = OccasionFrequency::cases();
        $age_groups = AgeGroup::cases();
        $days = WeekDay::cases();

        $group_tags = $group->tags->pluck('tag')->all();

        $age_group_array = array_filter(explode(',', $group->age_group));
        $group_days = $group->getDays();
        $view = 'portal.group.my_group';
        $action = route('portal.my_group.update');
        $institute = $this->institutes->find($group->institute_id);

        return view($view, compact(
            'group',
            'institute',
            'statuses',
            'occasion_frequencies',
            'age_groups',
            'action',
            'tags',
            'age_group_array',
            'group_tags',
            'days',
            'group_days',
            'group_tags'
        ));
    }
}
