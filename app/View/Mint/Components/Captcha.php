<?php

declare(strict_types=1);

namespace App\View\Mint\Components;

use App\Services\Captcha\Cloudflare\Component as CfComponent;
use Baueri\Mint\Context;
use Baueri\Mint\Module\Module;

class Captcha extends Module
{
    public function render(Context $context): string
    {
        return app()->get(CfComponent::class)->render();
    }
}
