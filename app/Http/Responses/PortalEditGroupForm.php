<?php

namespace App\Http\Responses;

use App\Enums\AgeGroup;
use App\Enums\WeekDay;
use App\Models\ChurchGroupView;
use App\Repositories\GroupStatusRepository;
use App\Repositories\Institutes;
use App\Repositories\OccasionFrequencies;

class PortalEditGroupForm
{
    private Institutes $institutes;

    public function __construct(Institutes $institutes)
    {
        $this->institutes = $institutes;
    }

    public function __invoke(ChurchGroupView $group): string
    {
        $statuses = (new GroupStatusRepository())->all();
        $tags = builder('tags')->select()->get();
        $spiritual_movements = db()->select('select * from spiritual_movements order by name');
        $occasion_frequencies = (new OccasionFrequencies())->all();
        $age_groups = AgeGroup::cases();
        $days = WeekDay::cases();

        $group_tags = collect(
            builder('group_tags')
            ->apply('whereGroupId', $group->getId())
            ->get()
        )->pluck('tag')->all();

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
            'spiritual_movements',
            'tags',
            'age_group_array',
            'group_tags',
            'days',
            'group_days',
            'group_tags'
        ));
    }
}
