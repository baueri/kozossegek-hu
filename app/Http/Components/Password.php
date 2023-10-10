<?php

declare(strict_types=1);

namespace App\Http\Components;

class Password extends TextInput
{
    public function __construct(
        string $label = '',
        string $required = '',
        string $info = '',
        string $size = '',
        string $name = '',
        string $class = '',
        string $value = '',
    ) {
        parent::__construct($label, $required, $info, $size, $name, 'password', $class, $value);
    }
}