<?php

namespace App\Services\SystemAdministration;

use App\Repositories\Groups;
use App\Services\RebuildSearchEngine;

/**
 * Több, mint egy éve törölt közösségek törlése
 *
 * @package App\Services\SystemAdministration
 */
class ForceDeleteDeletedChurchGroups
{
    private Groups $groups;

    private RebuildSearchEngine $rebuildSearchEngine;

    public function __construct(Groups $groups, RebuildSearchEngine $rebuildSearchEngine)
    {
        $this->groups = $groups;
        $this->rebuildSearchEngine = $rebuildSearchEngine;
    }

    public function run()
    {
        $groups = $this->groups->getInstances(
            builder('church_groups')
                ->apply('deletedEarlierThan', date('Y-m-d', strtotime('-1 year')))
                ->get()
        );

        $this->groups->deleteMultiple($groups, true);
    }
}
