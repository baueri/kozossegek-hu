<?php

namespace App\Portal\Controllers\Api\V1;

use Framework\Http\Request;
use App\Repositories\CityRepository;
use App\Repositories\DistrictRepository;
use App\Repositories\InstituteRepository;
use App\Portal\Responses\DistrictResponse;
use App\Portal\Responses\CitySearchResponse;
use App\Portal\Responses\InstituteSearchResponse;

/**
 * Description of CitySearchController
 *
 * @author ivan
 */
class SearchController
{

    /**
     * @var Request
     */
    private $request;

    /**
     *
     * @param Request $request
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
     *
     * @param CityRepository $repository
     */
    public function searchCity(CityRepository $repository)
    {
        return new CitySearchResponse($repository->search($this->request['term']));
    }

    public function searchInstitute(InstituteRepository $repository)
    {
        return new InstituteSearchResponse($repository->search($this->request['term'], $this->request['city']));
    }

    public function searchDistrict(DistrictRepository $repository)
    {
        return new DistrictResponse($repository->searchDistrict($this->request['term'], $this->request['city']));
    }
}
