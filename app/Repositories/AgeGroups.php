<?php

namespace App\Repositories;

use App\Enums\AgeGroupEnum;
use App\Models\AgeGroup;
use ReflectionException;

class AgeGroups
{
    /**
     * @return AgeGroup[]
     * @throws ReflectionException
     */
    final public function all(): array
    {
        return AgeGroupEnum::values()->as(AgeGroup::class)->all();
    }
}
