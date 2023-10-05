<?php

namespace App\Http\Components;

use App\Enums\EnumTrait;
use App\Enums\HasTranslation;
use Framework\Support\Collection;

abstract class EnumSelector extends Select
{
    public function options(): ?string
    {
        return $this->getCases()->collect()->map(fn ($case) => "<option value=\"{$case->value()}\">{$case->translate()}</option>")->implode('');
    }

    /**
     * @return \BackedEnum[]|HasTranslation[]|EnumTrait[]
     */
    abstract protected function getCases(): Collection;
}
