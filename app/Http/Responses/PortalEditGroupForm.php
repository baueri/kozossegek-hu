<?php

namespace App\Http\Responses;

use App\Enums\DayEnum;
use App\Models\GroupView;
use App\Models\User;
use App\Repositories\AgeGroups;
use App\Repositories\Denominations;
use App\Repositories\GroupStatusRepository;
use App\Repositories\OccasionFrequencies;

/**
 * Description of PortalEditGroupForm
 *
 * @author ivan
 */
class PortalEditGroupForm
{
    public function getResponse(GroupView $group)
    {
        $statuses = (new GroupStatusRepository())->all();
        $tags = builder('tags')->select('*')->get();
        $spiritual_movements = db()->select('select * from spiritual_movements order by name');
        $occasion_frequencies = (new OccasionFrequencies())->all();
        $age_groups = (new AgeGroups())->all();
        $denominations = (new Denominations())->all();
        $days = DayEnum::all();

        $group_tags = collect(builder('group_tags')->whereGroupId($group->id)->get())->pluck('tag')->all();
        $age_group_array = array_filter(explode(',', $group->age_group));
        $images = $group->getImages();
        $group_days = explode(',', $group->on_days);
        $view = 'portal.group.edit_my_group';
        $action = route('portal.my_group.update');


        return view($view, compact(
            'group',
            'institute',
            'denominations',
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
            'images',
            'group_tags'
        ));
    }
}
