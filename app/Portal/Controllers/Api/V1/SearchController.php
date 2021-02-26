<?php

namespace App\Portal\Controllers\Api\V1;

use App\Auth\Auth;
use Framework\Http\Request;
use App\Repositories\Cities;
use App\Repositories\Districts;
use App\Repositories\Institutes;
use App\Repositories\Users;
use App\Portal\Responses\DistrictResponse;
use App\Portal\Responses\CitySearchResponse;
use App\Portal\Responses\InstituteSearchResponse;
use App\Portal\Responses\UserResponse;

/**
 * @author ivan
 */
class SearchController
{

    /**
     * @var Request
     */
    private Request $request;

    /**
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     *
     * @param Cities $repository
     * @return CitySearchResponse
     */
    public function searchCity(Cities $repository)
    {
        return new CitySearchResponse($repository->search($this->request['term']));
    }

    public function searchCitiesByExistingInstitutes(Cities $repository)
    {
        return new CitySearchResponse($repository->search($this->request['term']));
    }

    public function searchInstitute(Institutes $repository)
    {
        $user = Auth::user();
        $response = new InstituteSearchResponse($repository->search($this->request['term'], $this->request['city']));
        return $response->asAdmin($user && $user->isAdmin());
    }

    public function searchDistrict(Districts $repository)
    {
        return new DistrictResponse($repository->searchDistrict($this->request['term'], $this->request['city']));
    }

    public function searchUser(Users $users)
    {
        return new UserResponse($users->searchUsers($this->request['term']));
    }
}
