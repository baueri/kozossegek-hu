<?php


namespace App\Admin\Controllers;

use App\Admin\Controllers\AdminController;
use App\Admin\Group\Services\BaseGroupForm;
use App\Admin\Group\Services\CreateGroup;
use App\Admin\Group\Services\DeleteGroup;
use App\Admin\Group\Services\EditGroup;
use App\Admin\Group\Services\ListGroups;
use App\Admin\Group\Services\UpdateGroup;
use App\Repositories\Groups;
use App\Services\RebuildSearchEngine;
use Framework\Http\Message;
use Framework\Http\Request;

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
        $group = $service->create($request);
        
        Message::success('Közösség létrehozva.');

        redirect_route('admin.group.edit', ['id' => $group->id]);
    }

    public function edit(EditGroup $service)
    {
        return $service->show();
    }

    public function update(Request $request, UpdateGroup $service)
    {
        $service->update($request['id'], $request);

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
    
    public function rebuildSearchEngine(RebuildSearchEngine $service)
    {
        $service->updateAll();
        
        Message::success('Sikeres keresőmotor frissítés');
        
        return redirect_route('admin.group.list');
    }

    /**
     * @param Request $request
     * @param Groups $groups
     */
    public function restore(Request $request, Groups $groups)
    {
        $group = $groups->find($request['id']);
        $group->deleted_at = null;
        $groups->save($group);

        Message::success('Közösség sikeresen visszaállítva.');

        redirect_route('admin.group.edit', $group);
    }
}
