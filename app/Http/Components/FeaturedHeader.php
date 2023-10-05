<?php

namespace App\Http\Components;

use Framework\Http\View\TagComponent;

class FeaturedHeader extends TagComponent
{
    public function __construct(
        private readonly ?string $sectionClass = null
    ) {
    }

    public function render(): string
    {
        return <<<HTML
            <section class="section section-with-bg is-bold {$this->sectionClass}">
                <div class="container is-max-desktop">
                    {$this->slot}         
                </div>
            </section>
        HTML;
    }
}