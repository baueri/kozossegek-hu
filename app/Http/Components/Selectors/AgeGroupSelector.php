<?php

namespace App\Http\Components\Selectors;

use App\Enums\AgeGroup;
use Framework\Http\View\Component;

class AgeGroupSelector extends Component
{
    private $age_group_array;

    public function __construct($age_group_array)
    {
        $this->age_group_array = $age_group_array;
    }

    public function render(): string
    {
        $age_groups = AgeGroup::cases();
        $age_group_array = $this->age_group_array;
        return view('partials.components.age_group_selector', compact('age_groups', 'age_group_array'));
    }
}
