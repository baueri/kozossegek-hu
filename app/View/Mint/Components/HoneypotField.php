<?php

declare(strict_types=1);

namespace App\View\Mint\Components;

use App\Helpers\HoneyPot;
use Baueri\Mint\Context;
use Baueri\Mint\Module\Module;

class HoneypotField extends Module
{
    public function render(Context $context): string
    {
        $id = (string) ($context->resolve('id') ?? 'default');

        HoneyPot::setTime($id);

        return '<input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off" value="">';
    }
}
