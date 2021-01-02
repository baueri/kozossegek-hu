<?php

namespace App\Http\Components\Selectors;

use App\Enums\JoinMode;

class JoinModeSelector
{
    public function render($selected_join_mode)
    {
        $join_modes = JoinMode::getModesWithName();
        return view('partials.components.join_mode_selector', compact('join_modes', 'selected_join_mode'));
    }
}
