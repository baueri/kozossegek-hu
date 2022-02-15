<?php

namespace App\Portal\Controllers;

class HomeController extends PortalController
{
    public function home(): string
    {
        use_default_header_bg();
        return view('portal.home');
    }
}
