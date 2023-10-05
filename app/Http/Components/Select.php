<?php

namespace App\Http\Components;

class Select extends HorizontalInput
{
    public function __construct(
        string $label = '',
        string $required = '',
        string $info = '',
        string $size = '',
        protected readonly string $name = '',
        protected readonly string $multiple = '',
    ) {
        parent::__construct($label, $required, $info, $size);
    }

    public function getFormField(): string
    {
        $multipleClass = $this->multiple ? 'is-multiple' : '';
        $required = $this->required ? 'required' : '';
        return <<<HTML
            <div class="select {$multipleClass}">
                <select name="{$this->name}" {$required} multiple="{$this->multiple}">
                    {$this->options()}
                </select>
            </div>
        HTML;
    }

    public function options(): ?string
    {
        return $this->slot;
    }
}