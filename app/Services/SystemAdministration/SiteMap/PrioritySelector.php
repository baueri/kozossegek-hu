<?php

namespace App\Services\SystemAdministration\SiteMap;

use Framework\Http\View\Component;

class PrioritySelector extends Component
{
    public function __construct(
        private readonly ?string $priority
    ) {
    }

    public function render(): string
    {
        $priorities = collect(range(0.0, 1.0, 0.1))
            ->map(function ($priority) {
                $selected = selected((string) $priority === $this->priority);
                return "<option {$selected}>{$priority}</option>";
            })->implode('');

        return <<<HTML
            <select name="priority" class="form-control">
                 $priorities
            </select>
        HTML;
    }
}