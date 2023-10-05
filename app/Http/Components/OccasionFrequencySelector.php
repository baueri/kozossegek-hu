<?php

namespace App\Http\Components;

use App\Enums\OccasionFrequency;
use Framework\Support\Collection;

class OccasionFrequencySelector extends EnumSelector
{

    protected function getCases(): Collection
    {
        return OccasionFrequency::collect();
    }
}