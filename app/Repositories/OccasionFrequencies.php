<?php

namespace App\Repositories;

use App\Enums\OccasionFrequencyEnum;
use App\Models\OccasionFrequency;
use ReflectionException;

/**
 * Description of OccasionFrequencies
 *
 * @author ivan
 */
class OccasionFrequencies {

    /**
     *
     * @return OccasionFrequency[]
     * @throws ReflectionException
     */
    public function all()
    {
        return OccasionFrequencyEnum::values()->make(OccasionFrequency::class)->all();
    }
}