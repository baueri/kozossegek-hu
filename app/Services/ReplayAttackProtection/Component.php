<?php

declare(strict_types=1);

namespace App\Services\ReplayAttackProtection;

class Component extends \Framework\Http\View\Component
{
    public function __construct(
        protected readonly string $name,
        protected readonly Service $service
    ) {
    }

    public function render(): string
    {
        $nonce = $this->service->generateNonce($this->name);

        return <<<HTML
            <input type="hidden" name="rap" value="{$nonce}" />
        HTML;
    }

}