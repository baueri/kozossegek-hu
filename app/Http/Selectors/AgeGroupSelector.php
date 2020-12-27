<?php

namespace App\Http\Selectors;

use App\Repositories\AgeGroups;

/**
 * Description of AgeGroupSelector
 *
 * @author ivan
 */
class AgeGroupSelector
{

    public function render($age_group_array, array $data = [])
    {
        $age_groups = (new AgeGroups())->all();
        return view("partials.components.age_group_selector", array_merge($data, compact("age_groups", "age_group_array")));
    }
}
