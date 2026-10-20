<?php

declare(strict_types=1);

namespace App\Portal\Services;

use App\Admin\Group\Services\UpdateGroup;
use App\Models\ChurchGroup;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\Institutes;
use App\Services\RebuildSearchEngine;
use App\Services\Webhook\GroupWebhookDispatcher;
use Framework\File\FileManager;
use Framework\Support\Collection;

class PortalUpdateGroup extends UpdateGroup
{
    public function __construct(
        ChurchGroups $repository,
        RebuildSearchEngine $searchEngineBuilder,
        FileManager $fileManager,
        Institutes $institutes,
        private readonly GroupWebhookDispatcher $webhooks,
    ) {
        parent::__construct($repository, $searchEngineBuilder, $fileManager, $institutes);
    }

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

        $this->webhooks->dispatch(GroupWebhookDispatcher::EVENT_UPDATED, $group);

        return $group;
    }
}
