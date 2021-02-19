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
    /**
     * @var Groups
     */
    private Groups $groups;
    /**
     * @var Institutes
     */
    private Institutes $institutes;

    public function __construct(Groups $groups, Institutes $institutes)
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
            $image = preg_replace('#^http://kozossegek.local#', '', $group->getFirstImage());

            if ($image === '/images/default_thumbnail.jpg' && $institute->hasImage()) {
                $image = $institute->getImageRelPath();
            }
            $group->image_url = $image;
            $this->groups->update($group);
        }
    }
}
