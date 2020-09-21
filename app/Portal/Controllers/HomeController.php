<?php

namespace App\Portal\Controllers;

/**
 * Description of HomeController
 *
 * @author ivan
 */
class HomeController extends \Framework\Http\Controller
{
    public function home(\App\Repositories\AgeGroupRepository $ageGroupRepository)
    {
        $model = [
            'age_groups' => $ageGroupRepository->all()
        ];
        return $this->view('portal.home', $model);
    }
}
