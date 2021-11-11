<?php

namespace App\Repositories;

use App\Enums\GroupStatusEnum;
use App\Models\GroupStatus;
use ReflectionException;

/**
 * Description of GroupStatusRepository
 *
 * @author ivan
 */
class GroupStatusRepository {

    /**
     *
     * @return GroupStatus[]
     * @throws ReflectionException
     */
    public function all() : array
    {
        return GroupStatusEnum::values()->as(GroupStatus::class)->all();
    }
}
