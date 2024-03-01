<?php

namespace App\Admin\Group\Services;

use App\Helpers\GroupHelper;
use App\QueryBuilders\ChurchGroups;
use Framework\Http\Message;
use Framework\Model\Exceptions\ModelNotFoundException;
use Google\Service\AdExchangeBuyer\Resource\Pubprofiles;

class DeleteGroup
{
    public function __construct(
        private readonly ChurchGroups $repository
    ) {
    }

    /**
     * @throws ModelNotFoundException
     */
    public function delete(int $groupId): void
    {
        $group = $this->repository->findOrFail($groupId);

        $this->repository->softDelete($group);

        Message::warning('Közösség lomtárba helyezve.');
    }

    public function hardDelete(int $groupId): void
    {
        $group = $this->repository->findOrFail($groupId);

        $this->repository->hardDeleteModel($group);

        rrmdir(GroupHelper::getStoragePath($groupId));

        Message::warning('Közösség törölve.');
    }
}
