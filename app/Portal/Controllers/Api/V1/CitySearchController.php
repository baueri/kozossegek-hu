<?php

namespace App\Portal\Controllers\Api\V1;

/**
 * Description of CitySearchController
 *
 * @author ivan
 */
class CitySearchController extends \Framework\Http\Controller
{
    
    /**
     * 
     * @param \App\Repositories\CityRepository $repository
     */
    public function search(\Framework\Http\Request $request, \App\Repositories\CityRepository $repository)
    {
        return (new \App\Portal\Responses\CitySearchResponse($repository->search($request['term'])));
    }
}
