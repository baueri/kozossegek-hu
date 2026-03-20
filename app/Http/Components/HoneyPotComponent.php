<?php

namespace App\Http\Components;

use App\Helpers\HoneyPot;

class HoneyPotComponent
{
    public function render(?string $name = null): string
    {
        $nvr = 'a_' . substr(bin2hex(random_bytes(32)), 0, 5);
        HoneyPot::setTime($name ?? request()->uri);
        return <<<EOT
            <input type="text" name="website" data-required="1" id="$nvr" value="" style="width: 0;padding: 0;border: 0;margin: 0;">
        EOT;
    }
}
