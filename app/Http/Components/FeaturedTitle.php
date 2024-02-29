<?php

declare(strict_types=1);

namespace App\Http\Components;

class FeaturedTitle
{
    public function render(string $title): string
    {
        return view('portal.partials.featured-title', compact('title'));
    }
}
