<?php


namespace App\Admin\Controllers;

use App\Admin\Group\Services\BaseGroupForm;
use App\Admin\Group\Services\CreateGroup;
use App\Admin\Group\Services\DeleteGroup;
use App\Admin\Group\Services\EditGroup;
use App\Admin\Group\Services\ListGroups;
use App\Admin\Group\Services\UpdateGroup;
use App\Admin\Group\Services\ValidateGroupForm;
use App\Http\Exception\RequestParameterException;
use App\Models\Group;
use App\Models\GroupView;
use App\Repositories\Groups;
use App\Repositories\GroupViews;
use App\Services\CreateUserFromGroup;
use App\Services\RebuildSearchEngine;
use Framework\Http\Message;
use Framework\Http\Request;
use ReflectionException;

class GroupController extends AdminController
{
    /**
     * @param ListGroups $service
     * @return string
     * @throws ReflectionException
     */
    public function list(ListGroups $service)
    {
        return $service->show();
    }

    /**
     * @param BaseGroupForm $service
     * @return string
     */
    public function create(BaseGroupForm $service): string
    {
        return $service->render(new Group());
    }

    public function doCreate(Request $request, CreateGroup $service, BaseGroupForm $form)
    {
        try {
            $group = $service->create($request->collect());

            Message::success('Közösség létrehozva.');

            redirect_route('admin.group.edit', ['id' => $group->id]);

        } catch (RequestParameterException $e) {
            Message::danger($e->getMessage());
            return $form->render(new Group($request->all()));
        }
    }

    public function edit(Request $request, EditGroup $service, GroupViews $repository)
    {
        return $service->render($repository->findOrFail($request['id']));
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

    public function createUserFromGroup(Request $request, Groups $groups, CreateUserFromGroup $service)
    {
        return 'majd';
//        $group = $groups->findOrFail($request['id']);
//
//        $user = $service->createUserAndAddToGroup($group);
    }

    public function createMissingUsers(Request $request, GroupViews $groupRepository, CreateUserFromGroup $service)
    {
        $template = $request['email_template'];

        $groups = $groupRepository->getGroupsWithoutUser();

        foreach ($groups as $group) {
            $service->createUserAndAddToGroup($group, $template);
        }

        Message::success('Felhasználói fiókok létrehozva, továbbá a regisztrációs email-ek kiküldésre kerültek!');

        redirect_route('admin.group.maintenance');
    }

    public function maintenance(GroupViews $groupRepository)
    {
        $groups = $groupRepository->getGroupsWithoutUser();

        return view('admin.group.maintenance', compact('groups'));
    }

    public function validate(Request $request, GroupViews $groupViews, ValidateGroupForm $form)
    {
        /* @var $group GroupView */
        $group = $groupViews->findOrFail($request['id']);

        return $form->render($group);
    }

    public function getRejectModal(Request $request, GroupViews $groupViews)
    {
        /* @var $group GroupView */
        $group = $groupViews->findOrFail($request['id']);
        $message = <<<EOT
        Kedves {$group->group_leaders}!
        
        A közösséged adatlapja javításra szorul! Kérjük ellenőrizd az adatokat!
        EOT;

        return view('admin.group.reject-form', compact('group', 'message'));
    }
}
