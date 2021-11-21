<?php

namespace App\Portal\Services;

use App\Admin\Group\Services\UpdateGroup;
use Legacy\Group;

class PortalUpdateGroup extends UpdateGroup
{
    public function update(Group $group, $request, ?array $document = []): Group
    {
        if ($group->isDeleted()) {
            raise_404();
        }

        parent::update($group, $request, $document);

        if ($group->isRejected() && $group->hasChanges()) {
            $this->repository->update($group->setToPending());
        }

        return $group;
    }
}
