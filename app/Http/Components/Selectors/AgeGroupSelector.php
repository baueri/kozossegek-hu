<?php

namespace App\Http\Components\Selectors;

use App\Enums\AgeGroup;

class AgeGroupSelector
{
    public function render($age_group_array, array $data = []): string
    {
        $age_groups = AgeGroup::cases();
        return view('partials.components.age_group_selector', array_merge($data, compact('age_groups', 'age_group_array')));
    }
}
