<?php

namespace App\Repositories;

use App\Enums\GroupStatusEnum;
use App\Models\GroupStatus;

class GroupStatusRepository
{
    /**
     * @return GroupStatus[]
     */
    public function all(): array
    {
        return GroupStatusEnum::values()->as(GroupStatus::class)->all();
    }
}
