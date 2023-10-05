<?php

declare(strict_types=1);

namespace App\Http\Components;

use Framework\Http\View\TagComponent;

class Fieldset extends TagComponent
{
    public function __construct(
        private readonly ?string $legend = null
    ) {
    }

    public function render(): string
    {
        return <<<HTML
            <fieldset class="box" style="border: 1px solid #ddd">
                <legend class="is-size-5 pr-2 pl-2">{$this->legend}</legend>
                {$this->slot}
            </fieldset>
        HTML;
    }
}