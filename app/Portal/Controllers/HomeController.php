<?php

namespace App\Portal\Controllers;

use Framework\Http\Controller;

/**
 * Description of HomeController
 *
 * @author ivan
 */
class HomeController extends Controller
{
    public function home()
    {
        use_default_header_bg();

        return view('portal.home');
    }
}
