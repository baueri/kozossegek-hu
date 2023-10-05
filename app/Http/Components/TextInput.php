<?php

namespace App\Http\Components;

class TextInput extends HorizontalInput
{
    public function __construct(
        string $label = '',
        string $required = '',
        string $info = '',
        string $size = '',
        protected readonly string $name = '',
        protected readonly string $type = 'text',
        protected readonly string $class = '',
        protected readonly string $value = '',
    ) {
        parent::__construct($label, $required, $info, $size);
    }

    protected function getFormField(): string
    {
        $required = $this->required ? 'required' : '';
        return "<input type=\"{$this->type}\" name=\"{$this->name}\" class=\"input {$this->class}\" value=\"{$this->value}\" $required>";
    }
}