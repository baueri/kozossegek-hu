<?php

namespace App\Http\Components\Selectors;

use App\Repositories\OccasionFrequencies;

/**
 * Description of OccasionFrequencySelector
 *
 * @author ivan
 */
class OccasionFrequencySelector
{
    public function render($selected_occasion_frequency = null)
    {
        $occasion_frequencies = (new OccasionFrequencies())->all();
        return view("partials.components.occasion_frequency_selector", array_merge(compact("occasion_frequencies", "selected_occasion_frequency")));
    }
}
