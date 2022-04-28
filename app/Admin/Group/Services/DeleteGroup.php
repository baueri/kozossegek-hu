<?php

namespace App\Admin\Group\Services;

use App\Helpers\GroupHelper;
use App\Repositories\Groups;
use Framework\Http\Message;
use Framework\Model\ModelNotFoundException;

class DeleteGroup
{

    private Groups $repository;

    public function __construct(Groups $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws ModelNotFoundException
     */
    public function delete(int $groupId): void
    {
        $group = $this->repository->findOrFail($groupId);

        $this->repository->delete($group);

        rrmdir(GroupHelper::getStoragePath($groupId));

        Message::warning('Közösség lomtárba helyezve.');
    }
}
