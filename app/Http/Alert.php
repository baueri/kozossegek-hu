<?php

declare(strict_types=1);

namespace App\Http;

class Alert
{
    public function render($message, $level = 'info')
    {
        return view('partials.alert', compact('message', 'level'));
    }
}
