<?php

declare(strict_types=1);

namespace Framework\Http\View;

abstract class TagComponent extends Component
{
    public ?string $slot;

    public function withSlot(?string $slot): self
    {
        $this->slot = $slot;
        return $this;
    }
}