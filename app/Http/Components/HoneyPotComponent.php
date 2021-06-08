<?php

namespace App\Http\Components;

use App\Helpers\HoneyPot;

class HoneyPotComponent
{
    public function render()
    {
        $nvr = 'a_' . substr(md5(time()), 0, 5);
        $hash = HoneyPot::getHash(request()->uri);
        return <<<EOT
            <input type="text" name="website" id="$nvr" value="$hash" style="width: 0px;padding: 0;border: 0;margin: 0;">
        EOT;
    }
}
