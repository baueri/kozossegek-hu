<?php

namespace App\Http\Responses;

use App\Enums\DayEnum;
use App\Models\GroupView;
use App\Repositories\AgeGroups;
use App\Repositories\GroupStatusRepository;
use App\Repositories\Institutes;
use App\Repositories\OccasionFrequencies;

/**
 * Description of PortalEditGroupForm
 *
 * @author ivan
 */
class PortalEditGroupForm
{
    /**
     * @var Institutes
     */
    private Institutes $institutes;

    public function __construct(Institutes $institutes)
    {
        $this->institutes = $institutes;
    }

    public function getResponse(GroupView $group)
    {
        $statuses = (new GroupStatusRepository())->all();
        $tags = builder('tags')->select('*')->get();
        $spiritual_movements = db()->select('select * from spiritual_movements order by name');
        $occasion_frequencies = (new OccasionFrequencies())->all();
        $age_groups = (new AgeGroups())->all();
        $days = DayEnum::asArray();

        $group_tags = collect(builder('group_tags')->whereGroupId($group->id)->get())->pluck('tag')->all();
        $age_group_array = array_filter(explode(',', $group->age_group));
        $group_days = explode(',', $group->on_days);
        $view = 'portal.group.edit_my_group';
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
