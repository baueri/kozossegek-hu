<?php

namespace App\Repositories;

use App\Enums\AgeGroupEnum;
use App\Models\AgeGroup;
use ReflectionException;

class AgeGroupRepository {

    /**
     * @return AgeGroup[]
     * @throws ReflectionException
     */
    public function all() : array
    {
        return AgeGroupEnum::values()->make(AgeGroup::class)->all();
    }
}
