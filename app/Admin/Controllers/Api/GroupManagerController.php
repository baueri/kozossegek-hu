<?php

declare(strict_types=1);

namespace App\Admin\Controllers\Api;

use App\Admin\Controllers\AdminController;
use App\Portal\Responses\Select2Response;
use App\Services\GroupSearchRepository;
use Framework\Http\Request;

class GroupManagerController extends AdminController
{
    public function searchGroups(Request $request, GroupSearchRepository $repository): array|Select2Response
    {
        if (!$search = $request['q']) {
            return [];
        }
        $repository->repository->whereDoesnExist(
            builder('managed_church_groups')
            ->whereRaw("group_id={$repository->repository->getTable()}.id")
            ->where('user_id', $request['id']));

        return new class(
            $repository
                ->search(['search' => $search], 10)
        ) extends Select2Response {
            /**
             * @param \App\Models\ChurchGroupView $group
             * @return mixed
             */
            public function getText($group)
            {
                return "#{$group->id} - {$group->name} ({$group->city}, {$group->institute_name})";
            }

            public function getId($model)
            {
                return $model->getId();
            }
        };
    }

    public function addGroup(Request $request): array
    {
        builder('managed_church_groups')->insert($request->only(['user_id', 'group_id']));
        return api()->ok();
    }

    public function removeGroup(Request $request): array
    {
        builder('managed_church_groups')
            ->where('user_id', $request['user_id'])
            ->where('group_id', $request['group_id'])
            ->delete();

        return api()->ok();
    }
}
