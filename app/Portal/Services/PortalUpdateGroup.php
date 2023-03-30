<?php

declare(strict_types=1);

namespace App\Portal\Services;

use App\Admin\Group\Services\UpdateGroup;
use App\Models\ChurchGroup;
use Framework\Support\Collection;

class PortalUpdateGroup extends UpdateGroup
{
    public function update(ChurchGroup $group, Collection $request, ?array $document = []): ChurchGroup
    {
        if ($group->isDeleted()) {
            raise_404();
        }

        $data = $request->collect();

        if ($group->notified_at) {
            $data['notified_at'] = null;
            $data['confirmed_at'] = now();
        }

        parent::update($group, $data, $document);

        if ($group->isRejected() && $group->hasChanges()) {
            $this->repository->update(['pending' => '1']);
        }

        return $group;
    }
}
