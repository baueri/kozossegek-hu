<?php

namespace App\Http\Components\Selectors;

use App\Enums\WeekDay;
use Framework\Http\View\Component;
use Framework\Support\Collection;

class OnDaysSelector extends Component
{
    /**
     * @param \Framework\Support\Collection<WeekDay> $group_days
     */
    public function __construct(private readonly Collection $group_days)
    {
    }

    public function render(): string
    {
        $days = WeekDay::cases();
        return view('partials.components.on_days_selector', ['days' => $days, 'group_days' => $this->group_days->pluck('name')->all()]);
    }
}
