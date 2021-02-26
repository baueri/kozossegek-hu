<?php

namespace App\Services\SystemAdministration;

use App\Models\Group;
use App\Models\GroupView;
use App\Models\Institute;
use App\Repositories\Groups;
use App\Repositories\GroupViews;
use App\Repositories\Institutes;
use Framework\Support\Collection;

class SetImageUrlForMissingGroups
{
    private GroupViews $groups;
    /**
     * @var Institutes
     */
    private Institutes $institutes;

    public function __construct(GroupViews $groups, Institutes $institutes)
    {
        $this->groups = $groups;
        $this->institutes = $institutes;
    }

    public function run()
    {
        /* @var $groups GroupView[]|Collection */
        $groups = $this->groups->all();
        foreach ($groups as $group) {
            /* @var $institute Institute */
            $institute = $this->institutes->find($group->institute_id);
            $image = $group->getThumbnail();

            if ($image === '/images/default_thumbnail.jpg' && $institute->hasImage()) {
                $image = $institute->getImageRelPath();
            }
            $group->image_url = $image;
            (app()->make(Groups::class))->update($group);
        }
    }
}
