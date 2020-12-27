<?php

namespace App\Http\Components;

class FeaturedTitle
{
    public function render(string $title)
    {
        return view('portal.partials.featured-title', compact('title'));
    }
}
