<?php

declare(strict_types=1);

namespace App\Http\Components;

use Framework\Http\View\TagComponent;

class HorizontalInput extends TagComponent
{
    public function __construct(
        private readonly string $type = 'text',
        private readonly string $label = '',
        private readonly string $name = '',
        private readonly string $value = '',
        private readonly string $required = '',
        private readonly string $class = '',
        private readonly string $info = '',
        private readonly string $size = 'is-narrow'
    ) {
    }

    public function render(): string
    {
        $required = $this->required ? 'required' : '';
        $requiredStar = $this->required ? '<span class="has-text-danger">*</span>' : '';
        $infoTooltip = $this->info ? " <span class=\"icon is-small is-right\" data-tooltip=\"{$this->info}\"><i class=\"fas fa-info-circle\"></i></span>" : "";
        return <<<HTML
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label">{$this->label}{$requiredStar}{$infoTooltip}</label>
                </div>
                <div class="field-body">
                    <div class="field {$this->size}">
                        <input type="$this->type" name="$this->name" class="input $this->class" value="$this->value" $required>
                    </div>
                </div>
            </div>
        HTML;
    }
}