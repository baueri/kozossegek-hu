<?php

namespace App\Portal\Controllers;

use Framework\Support\StringHelper;

class TestController
{
    public function test()
    {
        print StringHelper::generateRandomStringFrom('ABCDEFGHJKLMNPRSTUVWXYZ123456789', 5);
    }
}
