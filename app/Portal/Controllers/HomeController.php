<?php

namespace App\Portal\Controllers;

use App\Repositories\AgeGroups;
use Framework\Http\Controller;

/**
 * Description of HomeController
 *
 * @author ivan
 */
class HomeController extends Controller
{
    public function home(AgeGroups $AgeGroups)
    {
        $model = [
            'age_groups' => $AgeGroups->all()
        ];

        return view('portal.home', $model);
    }
}
