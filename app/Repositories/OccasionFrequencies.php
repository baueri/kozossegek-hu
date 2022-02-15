<?php

namespace App\Repositories;

use App\Enums\OccasionFrequencyEnum;
use App\Models\OccasionFrequency;
use ReflectionException;

class OccasionFrequencies
{

    /**
     * @return OccasionFrequency[]
     */
    public function all(): array
    {
        return OccasionFrequencyEnum::values()->as(OccasionFrequency::class)->all();
    }
}
