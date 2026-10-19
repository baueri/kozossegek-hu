<?php

declare(strict_types=1);

namespace App\View\Mint\Components;

use App\Services\ReplayAttackProtection\Component as RapComponent;
use App\Services\ReplayAttackProtection\Service;
use Baueri\Mint\Context;
use Baueri\Mint\Module\Module;

class ReplayAttack extends Module
{
    public function render(Context $context): string
    {
        $name = (string) ($context->resolve('name') ?? 'default');
        $service = app()->get(Service::class);

        return (new RapComponent($name, $service))->render();
    }
}
