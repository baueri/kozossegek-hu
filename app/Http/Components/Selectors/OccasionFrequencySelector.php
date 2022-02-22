<?php

namespace App\Http\Components\Selectors;

use App\Enums\OccasionFrequency;
use Framework\Http\View\Component;

class OccasionFrequencySelector extends Component
{
    public function render(?string $selected_occasion_frequency = null): string
    {
        $occasion_frequencies = OccasionFrequency::cases();
        return view("partials.components.occasion_frequency_selector", array_merge(compact("occasion_frequencies", "selected_occasion_frequency")));
    }
}
