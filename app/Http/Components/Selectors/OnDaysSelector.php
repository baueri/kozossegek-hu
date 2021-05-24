<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Components\Selectors;

use App\Enums\DayEnum;

/**
 * Description of OnDaysSelector
 *
 * @author ivan
 */
class OnDaysSelector
{

    public function render($group_days)
    {
        $days = DayEnum::asArray();
        return view('partials.components.on_days_selector', compact('days', 'group_days'));
    }
}
