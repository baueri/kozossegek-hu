<?php


namespace App\Admin\Group;


use App\Admin\Controllers\AdminController;
use App\Admin\Group\Services\ListGroups;
use App\Admin\Group\Services\EditGroup;
use App\Admin\Group\Services\UpdateGroup;
use Framework\Exception\UnauthorizedException;
use Framework\Http\Request;
use App\Admin\Group\Services\DeleteGroup;
use App\Admin\Group\Services\CreateGroup;
use App\Admin\Group\Services\BaseGroupForm;

class GroupController extends AdminController
{
    public function list(ListGroups $service)
    {
        return $service->show();
    }

    public function create(BaseGroupForm $service)
    {
        return $service->show();
    }

    public function doCreate(Request $request, CreateGroup $service)
    {
        $group = $service->create($request->except('files', 'image')->all());

        redirect_route('admin.group.edit', ['id' => $group->id]);
    }

    public function edit(EditGroup $service)
    {
        return $service->show();
    }

    public function update(Request $request, UpdateGroup $service)
    {
        $service->update($request['id'], $request->except('id')->all());

        redirect_route('admin.group.edit', ['id' => $request['id']]);
    }

    public function delete(Request $request, DeleteGroup $service)
    {
        $service->delete($request['id']);

        redirect_route('admin.group.list');
    }

    public function trash(ListGroups $service)
    {
        return $service->show();
    }
}
