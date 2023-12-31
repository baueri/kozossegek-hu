<?php

declare(strict_types=1);

namespace App\Http\Components\Selectors;

class SpiritualMovementSelector
{
    public function render($selected_spiritual_movement)
    {
        $spiritual_movements = db()->select('select * from spiritual_movements order by name');
        return view('partials.components.spiritual_movement_selector', compact('spiritual_movements', 'selected_spiritual_movement'));
    }
}
