<?php

namespace App\Portal\Controllers;

class HomeController extends PortalController
{
    public function home(): string
    {
        return view('portal2.home');
    }
}
