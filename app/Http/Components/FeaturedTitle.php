<?php


namespace App\Http\Components;


class FeaturedTitle
{
    public function render(string $title)
    {
        view('portal.partials.featured-title', compact('title'));
    }
}
