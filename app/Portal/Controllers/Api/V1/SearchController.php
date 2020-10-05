<?php

namespace App\Portal\Controllers\Api\V1;

use Framework\Http\Request;
use App\Repositories\Cities;
use App\Repositories\Districts;
use App\Repositories\Institutes;
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
     * @param Cities $repository
     */
    public function searchCity(Cities $repository)
    {
        return new CitySearchResponse($repository->search($this->request['term']));
    }

    public function searchInstitute(Institutes $repository)
    {
        return new InstituteSearchResponse($repository->search($this->request['term'], $this->request['city']));
    }

    public function searchDistrict(Districts $repository)
    {
        return new DistrictResponse($repository->searchDistrict($this->request['term'], $this->request['city']));
    }
}
