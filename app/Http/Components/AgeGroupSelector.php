<?php

namespace App\Http\Components;

use App\Enums\AgeGroup;
use Framework\Support\Collection;

class AgeGroupSelector extends EnumSelector
{
    protected function getCases(): Collection
    {
        return AgeGroup::collect();
    }
}