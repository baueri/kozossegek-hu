<?php

namespace App\Portal\Controllers;

use App\Repositories\AgeGroups;
use App\Repositories\Widgets;
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
        return view('portal.home');
    }
}
