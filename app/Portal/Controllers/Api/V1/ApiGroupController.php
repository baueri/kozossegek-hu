<?php

namespace App\Portal\Controllers\Api\V1;

use App\Http\Responses\CreateGroupSteps\FinishRegistration;
use App\Models\GroupView;
use App\Repositories\GroupViews;
use Exception;
use Framework\Http\Controller;
use Framework\Http\Request;

class ApiGroupController extends Controller
{
    /**
     * @param FinishRegistration $preview
     * @return string
     */
    public function previewGroup(FinishRegistration $preview)
    {
        return (string)$preview;
    }

    /**
     * @param Request $request
     * @param GroupViews $groupViews
     * @return array
     */
    public function list(Request $request, GroupViews $groupViews)
    {
        try {
            $filter = $request->only('search', 'varos', 'intezmeny');
            $perPage = $request['per_page'] ?: -1;
            return $groupViews->search($filter, $perPage)
                ->map(fn(GroupView $groupView) => [
                    'name' => $groupView->name,
                    'city' => $groupView->city,
                    'district' => $groupView->district,
                    'institute' => $groupView->institute_name,
                    'link' => $groupView->url(),
                ]);
        } catch (Exception $e) {
            error_log($e);
            return api()->error(['message' => 'unexpected_error']);
        }
    }
}
