<?php

namespace App\Portal\Services;

use App\Admin\Group\Services\UpdateGroup;
use App\Models\Group;
use Framework\Exception\FileTypeNotAllowedException;
use Framework\Http\Request;
use Framework\Support\Collection;

class PortalUpdateGroup extends UpdateGroup
{
    /**
     *
     * @param Group $group
     * @param Request|Collection|array $request
     * @param array|null $document
     * @return void
     * @throws FileTypeNotAllowedException
     */
    public function update(Group $group, $request, ?array $document = [])
    {
        if ($group->isDeleted()) {
            raise_404();
        }

        parent::update($group, $request, $document);

        if ($group->isRejected() && $group->hasChanges()) {
            $this->repository->update($group->setToPending());
        }
    }
}
