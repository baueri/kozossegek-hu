<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Selectors;

/**
 * Description of SpiritualMovementSelector
 *
 * @author ivan
 */
class SpiritualMovementSelector {
    public function render($selected_spiritual_movement)
    {
        $spiritual_movements = db()->select('select * from spiritual_movements order by name');   
        return view('partials.components.spiritual_movement_selector', compact('spiritual_movements', 'selected_spiritual_movement'));
    }
}
