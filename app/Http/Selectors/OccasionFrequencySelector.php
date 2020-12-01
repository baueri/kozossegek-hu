<?php
namespace App\Http\Selectors;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OccasionFrequencySelector
 *
 * @author ivan
 */
class OccasionFrequencySelector {
    
    public function render($selected_occasion_frequency = null)
    {
        $occasion_frequencies = (new \App\Repositories\OccasionFrequencies)->all();
        return view("partials.components.occasion_frequency_selector", array_merge(compact("occasion_frequencies", "selected_occasion_frequency")));
    }
}
