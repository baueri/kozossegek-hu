<?php

declare(strict_types=1);

namespace App\Portal\Controllers\Api\V1;

use App\Auth\Auth;
use App\Enums\Tag;
use App\Portal\Responses\CitySearchResponse;
use App\Portal\Responses\DistrictResponse;
use App\Portal\Responses\InstituteSearchResponse;
use App\Portal\Responses\UserResponse;
use App\QueryBuilders\Cities;
use App\QueryBuilders\Institutes;
use App\QueryBuilders\Users;
use App\Repositories\Districts;
use App\Services\MeiliSearch\GroupSearch;
use Framework\Http\Request;
use Framework\Support\Collection;
use Framework\Support\StringHelper;

class SearchController
{
    public const LIMIT = 5;

    public function __construct(
        private readonly Request $request
    ) {
    }

    public function searchCity(Cities $repository): CitySearchResponse
    {
        return new CitySearchResponse($repository->search($this->request['term'])->get());
    }

    public function searchInstitute(Institutes $repository): array|InstituteSearchResponse
    {
        if (!$this->request['term']) {
            return [];
        }
        $user = Auth::user();
        $response = new InstituteSearchResponse(
            $repository->when($this->request['city'], fn (Institutes $query, $city) => $query->where('city', $city))
                ->search($this->request['term'])
                ->orderBy('name', 'asc')
                ->paginate(15)
        );
        return $response->asAdmin((bool) $user?->isAdmin());
    }

    public function searchDistrict(Districts $repository): DistrictResponse
    {
        return new DistrictResponse($repository->searchDistrict($this->request['term'], $this->request['city']));
    }

    public function searchUser(Users $users): UserResponse
    {
        return new UserResponse($users->search($this->request['term'])->get());
    }

    public function searchGroup(GroupSearch $search): array
    {
        $query = $this->request->get('q');
        $ageGroup = $this->request->get('age_group');

        $params = $ageGroup ? ['filter' => ["age_group=\"{$ageGroup}\""]] : [];
        $params['attributesToHighlight'] = ['city', 'institute_name', 'name', 'tags', 'age_group'];

        $result = $search->search($query, $params, self::LIMIT);

        $total = $result->getEstimatedTotalHits();

        $formatted = collect($result->getHits())->map(function (array $group) {
            $tags = Tag::fromList($group['tag_ids'])->map->icon()->implode('');
            return view('portal.partials.api_group_result', ['group' => $group['_formatted'], 'tags' => $tags]);
        })->implode('');

        if ($total > self::LIMIT) {
            $more = route('portal.groups', ['search' => $query]);
            $formatted .= "<div class='text-center'><a href='{$more}' class='text-muted p-3 d-inline-block'>Összes találat ({$total})</a></div>";
        }

        return api()->response([
            'result' => $formatted,
            'total' => $total
        ]);
    }
}
