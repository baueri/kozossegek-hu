<?php

namespace App\Portal\Controllers;

class HomeController extends PortalController
{
    public function home()
    {
        use_default_header_bg();

        return view('portal.home');
    }
}
