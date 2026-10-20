<?php

namespace App\Http\Components\Selectors;

use App\Enums\AgeGroup;
use Framework\Http\View\Component;

class AgeGroupSelector extends Component
{
    public function __construct(
        private $age_group_array,
        private bool $required = false,
    ) {
    }

    public function render(): string
    {
        $age_groups = AgeGroup::cases();
        $age_group_array = $this->age_group_array;
        $required = $this->required;

        return view('partials.components.age_group_selector', compact('age_groups', 'age_group_array', 'required'));
    }
}
