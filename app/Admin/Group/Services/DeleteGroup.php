<?php

namespace App\Admin\Group\Services;

use App\Helpers\GroupHelper;
use App\QueryBuilders\ChurchGroups;
use App\Services\Webhook\GroupWebhookDispatcher;
use Framework\Http\Message;
use Framework\Model\Exceptions\ModelNotFoundException;

class DeleteGroup
{
    public function __construct(
        private readonly ChurchGroups $repository,
        private readonly GroupWebhookDispatcher $webhooks,
    ) {
    }

    /**
     * @throws ModelNotFoundException
     */
    public function delete(int $groupId): void
    {
        $group = $this->repository->findOrFail($groupId);
        $snapshot = $this->webhooks->snapshotForDelete($group);

        $this->repository->softDelete($group);
        $this->webhooks->dispatch(GroupWebhookDispatcher::EVENT_DELETED, $group, $snapshot);

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
