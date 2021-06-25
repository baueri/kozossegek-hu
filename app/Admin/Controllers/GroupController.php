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
use App\Mail\GroupAcceptedEmail;
use App\Mail\DefaultMailable;
use App\Models\Group;
use App\Models\GroupView;
use App\Repositories\Groups;
use App\Repositories\GroupViews;
use App\Services\RebuildSearchEngine;
use Exception;
use Framework\Exception\FileTypeNotAllowedException;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Http\View\View;
use Framework\Mail\Mailer;
use Framework\Model\Model;
use Framework\Model\ModelNotFoundException;
use ReflectionException;

class GroupController extends AdminController
{
    /**
     * @var Request
     */
    private Request $request;
    /**
     * @var GroupViews
     */
    private GroupViews $groupViews;

    public function __construct(Request $request, GroupViews $groupViews)
    {
        $this->request = $request;
        $this->groupViews = $groupViews;
    }

    /**
     * @param ListGroups $service
     * @return string
     * @throws Exception
     */
    public function list(ListGroups $service)
    {
        return $service->show();
    }

    /**
     * @param BaseGroupForm $service
     * @return string
     * @throws ReflectionException
     */
    public function create(BaseGroupForm $service): string
    {
        return $service->render(new GroupView());
    }

    /**
     * @param CreateGroup $service
     * @param BaseGroupForm $form
     * @return View|string
     * @throws ReflectionException
     */
    public function doCreate(CreateGroup $service, BaseGroupForm $form)
    {
        try {
            $group = $service->create($this->request->collect());
            event_logger()->logEvent('group_created', ['group_id' => $group->id]);

            Message::success('Közösség létrehozva.');
            redirect_route('admin.group.edit', ['id' => $group->id]);
            exit;
        } catch (Exception $e) {
            process_error($e->getMessage());
            Message::danger('Váratlan hiba történt!');
            return $form->render(new Group($this->request->all()));
        }
    }

    /**
     * @param EditGroup $service
     * @return View|string
     * @throws ModelNotFoundException|ReflectionException
     */
    public function edit(EditGroup $service)
    {
        return $service->render($this->findOrFailById());
    }

    /**
     * @param UpdateGroup $service
     * @param Groups $groups
     * @throws FileTypeNotAllowedException
     * @throws ModelNotFoundException
     */
    public function update(UpdateGroup $service, Groups $groups)
    {
        /* @var $group Group */
        $group = $groups->findOrFail($this->request['id']);
        $service->update($group, $this->request);

        redirect_route('admin.group.edit', ['id' => $this->request['id']]);
    }

    /**
     * @param DeleteGroup $service
     * @throws ModelNotFoundException
     */
    public function delete(DeleteGroup $service)
    {
        $service->delete($this->request['id']);

        redirect_route('admin.group.list');
    }

    /**
     * @param ListGroups $service
     * @return string
     * @throws ReflectionException
     */
    public function trash(ListGroups $service)
    {
        return $service->show();
    }

    /**
     * @param RebuildSearchEngine $service
     */
    public function rebuildSearchEngine(RebuildSearchEngine $service)
    {
        $service->run();

        Message::success('Sikeres keresőmotor frissítés');

        return redirect_route('admin.group.list');
    }

    /**
     * @param Groups $groups
     */
    public function restore(Groups $groups)
    {
        /* @var $group Group */
        $group = $groups->find($this->request['id']);
        $group->deleted_at = null;
        $groups->save($group);

        Message::success('Közösség sikeresen visszaállítva.');

        redirect_route('admin.group.edit', $group);
    }

    /**
     * @return View|string
     */
    public function maintenance()
    {
        return view('admin.group.maintenance');
    }

    /**
     * @param ValidateGroupForm $form
     * @return View|string
     * @throws ModelNotFoundException
     */
    public function validate(ValidateGroupForm $form)
    {
        $group = $this->findOrFailById();

        return $form->render($group);
    }

    /**
     * @return View|string
     * @throws ModelNotFoundException
     */
    public function getRejectModal()
    {
        $group = $this->findOrFailById();
        $message = view('mail.templates.reject-group', [
            'name' => $group->group_leaders,
            'edit_url' => $group->getEditUrl(),
            'group_name' => $group->name
        ]);

        return view('admin.group.reject-form', compact('group', 'message'));
    }

    /**
     * @param Groups $groups
     * @param Mailer $mailer
     * @return array
     */
    public function rejectGroup(Groups $groups, Mailer $mailer)
    {
        try {
            $this->request->validate('message', 'subject', 'email', 'name');

            $group = $this->findOrFailById();

            $groups->update($group, ['pending' => -1]);

            $mailable = DefaultMailable::make()
                ->setMessage($this->request['message'])
                ->subject("kozossegek.hu - {$this->request['subject']}");

            $mailer->to($this->request['email'], $this->request['name'])->send($mailable);

            return api()->ok();
        } catch (RequestParameterException $e) {
            return api()->error('Minden mező kötelező!');
        } catch (Exception $e) {
            return api()->error('Váratlan hiba történt!');
        }
    }

    /**
     * @throws ModelNotFoundException
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function approveGroup(Groups $groupRepo, Mailer $mailer): array
    {
        /* @var $groupView GroupView */
        $groupView = $this->groupViews->findOrFail($this->request['id']);

        $groupRepo->update($groupView->getGroup(), ['pending' => 0]);

        $mailable = new GroupAcceptedEmail($groupView);

        $mailer->to($groupView->group_leader_email, $groupView->group_leaders)
            ->send($mailable);

        return api()->ok();
    }

    /**
     * @throws ModelNotFoundException
     */
    public function getDeleteModal()
    {
        $group = $this->findOrFailById();

        $message = view('mail.templates.delete-group', [
            'name' => $group->group_leaders,
            'group_name' => $group->name
        ]);

        return view('admin.group.partials.delete_modal', compact('group', 'message'));
    }

    public function deleteByValidation(Groups $groups, Mailer $mailer)
    {
        try {
            $this->request->validate('message', 'subject', 'email', 'name');

            $group = $this->findOrFailById();

            $groups->delete($group);

            $mailable = DefaultMailable::make()
                ->setMessage($this->request['message'])
                ->subject("kozossegek.hu - {$this->request['subject']}");

            $mailer->to($this->request['email'], $this->request['name'])->send($mailable);

            return api()->ok();
        } catch (RequestParameterException $e) {
            return api()->error('Minden mező kötelező!');
        } catch (Exception $e) {
            return api()->error('Váratlan hiba történt!');
        }
    }

    /**
     * @return GroupView|Model
     * @throws ModelNotFoundException
     */
    private function findOrFailById(): GroupView
    {
        return $this->groupViews->findOrFail($this->request['id']);
    }
}
