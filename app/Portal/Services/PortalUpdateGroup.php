<?php

namespace App\Portal\Services;

use App\Admin\Group\Services\UpdateGroup;
use App\Models\ChurchGroup;
use Framework\Http\Request;
use Framework\Support\Collection;

class PortalUpdateGroup extends UpdateGroup
{
    public function update(ChurchGroup $group, Request|Collection|array $request, ?array $document = []): ChurchGroup
    {
        if ($group->isDeleted()) {
            raise_404();
        }

        parent::update($group, $request, $document);

        if ($group->isRejected() && $group->hasChanges()) {
            $this->repository->update(['pending' => '1']);
        }

        return $group;
    }
}
