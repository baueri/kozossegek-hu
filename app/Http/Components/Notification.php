<?php

namespace App\Http\Components;

use Framework\Http\View\TagComponent;

class Notification extends TagComponent
{
    public function __construct(
        private readonly string $type
    ) {
    }

    public function render(): string
    {
        return <<<HTML
        <div class="notification is-{$this->type} shadow-sm">
            {$this->slot}
        </div>
        HTML;
    }
}