<?php

namespace App\Admin\Group\Services;

use App\Helpers\GroupHelper;
use App\QueryBuilders\ChurchGroups;
use Framework\Http\Message;
use Framework\Model\Exceptions\ModelNotFoundException;

class DeleteGroup
{
    public function __construct(private readonly ChurchGroups $repository)
    {
    }

    /**
     * @throws ModelNotFoundException
     */
    public function delete(int $groupId): void
    {
        $group = $this->repository->findOrFail($groupId);

        $this->repository->softDelete($group);

        rrmdir(GroupHelper::getStoragePath($groupId));

        Message::warning('Közösség lomtárba helyezve.');
    }
}
