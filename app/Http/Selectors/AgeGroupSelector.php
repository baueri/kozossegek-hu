<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Selectors;

/**
 * Description of AgeGroupSelector
 *
 * @author ivan
 */
class AgeGroupSelector {
    
    public function render($age_group_array)
    {
        $age_groups = (new \App\Repositories\AgeGroups())->all();
        return view("partials.components.age_group_selector", array_merge(compact("age_groups", "age_group_array")));
    }
}
