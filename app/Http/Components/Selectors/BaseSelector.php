<?php

declare(strict_types=1);

namespace App\Http\Components\Selectors;

use Framework\Http\View\Component;

class BaseSelector extends Component
{
    public function __construct(
        private readonly ?string $placeholder = null,
        private readonly ?array $values = null,
        private readonly ?string $selected_value = null,
        private readonly ?string $name = null
    ) {
    }

    public function render(): string
    {
        return view('partials.components.base_selector', [
            'placeholder' => $this->placeholder,
            'values' => $this->values,
            'selected_value' => $this->selected_value,
            'name' => $this->name
        ]);
    }
}
