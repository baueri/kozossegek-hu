<?php

namespace App\Portal\Controllers;

use App\Repositories\AgeGroupRepository;
use Framework\Http\Controller;

/**
 * Description of HomeController
 *
 * @author ivan
 */
class HomeController extends Controller
{
    public function home(AgeGroupRepository $ageGroupRepository)
    {
        $model = [
            'age_groups' => $ageGroupRepository->all()
        ];

        return view('portal.home', $model);
    }
}
