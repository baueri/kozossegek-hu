<?php

namespace App\Portal\Controllers\Api\V1;

use App\Http\Responses\CreateGroupSteps\FinishRegistration;
use App\Models\ChurchGroupView;
use App\Services\GroupSearchRepository;
use Exception;
use Framework\Http\Controller;
use Framework\Http\Request;

class ApiGroupController extends Controller
{
    public function previewGroup(FinishRegistration $preview): string
    {
        return (string) $preview;
    }

    public function list(Request $request, GroupSearchRepository $groupViews): array
    {
        try {
            $filter = $request->only('search', 'varos', 'intezmeny');
            $perPage = (int) $request['per_page'] ?: 30;
            $results = $groupViews->search($filter, $perPage);

            $data = $results->map(fn (ChurchGroupView $groupView) => [
                'name' => $groupView->name,
                'city' => $groupView->city,
                'district' => $groupView->district,
                'institute' => $groupView->institute_name,
                'link' => $groupView->url(),
            ])->all();

            $query = array_filter([
                'search' => $request['search'],
                'varos' => $request['varos'],
                'intezmeny' => $request['intezmeny'],
                'per_page' => $results->perpage(),
                'page' => $results->page(),
                'total' => $results->total()
            ]);

            return compact('query', 'data');
        } catch (Exception $e) {
            error_log($e);
            return api()->error(['message' => 'unexpected_error']);
        }
    }
}
