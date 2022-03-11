<?php

namespace App\Portal\Controllers\Api\V1;

use App\Auth\Auth;
use App\Portal\Responses\CitySearchResponse;
use App\Portal\Responses\DistrictResponse;
use App\Portal\Responses\InstituteSearchResponse;
use App\Portal\Responses\UserResponse;
use App\QueryBuilders\Users;
use App\Repositories\Cities;
use App\Repositories\Districts;
use Framework\Http\Request;
use Legacy\Institutes;

class SearchController
{
    public function __construct(private Request $request)
    {
    }

    public function searchCity(Cities $repository): CitySearchResponse
    {
        return new CitySearchResponse($repository->search($this->request['term']));
    }

    public function searchCitiesByExistingInstitutes(Cities $repository): CitySearchResponse
    {
        return new CitySearchResponse($repository->search($this->request['term']));
    }

    public function searchInstitute(Institutes $repository): array|InstituteSearchResponse
    {
        if (!$this->request['term']) {
            return [];
        }

        $user = Auth::user();
        $response = new InstituteSearchResponse($repository->search($this->request['term'], $this->request['city']));
        return $response->asAdmin($user && $user->isAdmin());
    }

    public function searchDistrict(Districts $repository): DistrictResponse
    {
        return new DistrictResponse($repository->searchDistrict($this->request['term'], $this->request['city']));
    }

    public function searchUser(Users $users): UserResponse
    {
        return new UserResponse($users->search($this->request['term'])->get());
    }
}
