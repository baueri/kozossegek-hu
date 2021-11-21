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
use App\Mail\DefaultMailable;
use App\Mail\GroupAcceptedEmail;
use App\Models\ChurchGroupView;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\GroupViews;
use App\Repositories\Groups;
use App\Services\RebuildSearchEngine;
use Exception;
use Framework\Exception\FileTypeNotAllowedException;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Mail\Mailer;
use Framework\Model\ModelNotFoundException;
use Legacy\Group;
use ReflectionException;
use Throwable;

class GroupController extends AdminController
{
    private GroupViews $groupViews;

    public function __construct(Request $request, GroupViews $groupViews)
    {
        parent::__construct($request);
        $this->groupViews = $groupViews;
    }

    /**
     * @throws Exception
     */
    public function list(ListGroups $service): string
    {
        return $service->show();
    }

    public function create(BaseGroupForm $service): string
    {
        return $service->render(ChurchGroupView::make());
    }

    public function doCreate(CreateGroup $service, BaseGroupForm $form)
    {
        try {
            $group = $service->create($this->request->collect());
            event_logger()->logEvent('group_created', ['group_id' => $group->id]);

            Message::success('Közösség létrehozva.');
            redirect_route('admin.group.edit', ['id' => $group->id]);
        } catch (Exception $e) {
            process_error($e->getMessage());
            Message::danger('Váratlan hiba történt!');
            return $form->render(new Group($this->request->all()));
        }
    }

    /**
     * @throws ModelNotFoundException|ReflectionException
     */
    public function edit(EditGroup $service): string
    {
        return $service->render($this->findOrFailById());
    }

    /**
     * @throws FileTypeNotAllowedException
     * @throws ModelNotFoundException
     */
    public function update(UpdateGroup $service, Groups $groups)
    {
        $group = $groups->findOrFail($this->request['id']);
        $service->update($group, $this->request);

        redirect_route('admin.group.edit', ['id' => $this->request['id']]);
    }

    /**
     * @throws ModelNotFoundException
     */
    public function delete(DeleteGroup $service)
    {
        $service->delete($this->request['id']);

        redirect_route('admin.group.list');
    }

    public function trash(ListGroups $service): string
    {
        return $service->show();
    }

    public function rebuildSearchEngine(RebuildSearchEngine $service)
    {
        $service->run();

        Message::success('Sikeres keresőmotor frissítés');

        redirect_route('admin.group.list');
    }

    public function restore(Groups $groups)
    {
        $group = $groups->find($this->request['id']);
        $group->deleted_at = null;
        $groups->save($group);

        Message::success('Közösség sikeresen visszaállítva.');

        redirect_route('admin.group.edit', $group);
    }

    public function maintenance(): string
    {
        return view('admin.group.maintenance');
    }

    /**
     * @throws ModelNotFoundException
     */
    public function validate(ValidateGroupForm $form): string
    {
        $group = $this->findOrFailById();

        return $form->render($group);
    }

    /**
     * @throws ModelNotFoundException
     */
    public function getRejectModal(): string
    {
        $group = $this->findOrFailById();
        $message = view('mail.templates.reject-group', [
            'name' => $group->group_leaders,
            'edit_url' => $group->getEditUrl(),
            'group_name' => $group->name
        ]);

        return view('admin.group.reject-form', compact('group', 'message'));
    }

    public function rejectGroup(ChurchGroups $groups, Mailer $mailer): array
    {
        try {
            $this->request->validate('message', 'subject', 'email', 'name');

            $group = $this->findOrFailById();
            $groups->save($group, ['pending' => -1]);

            $mailable = app()->make(DefaultMailable::class)
                ->setMessage($this->request['message'])
                ->subject("kozossegek.hu - {$this->request['subject']}");

            $mailer->to($this->request['email'], $this->request['name'])->send($mailable);

            return api()->ok();
        } catch (RequestParameterException $e) {
            return api()->error('Minden mező kötelező!');
        } catch (Throwable $e) {
            report($e);
            return api()->error('Váratlan hiba történt!');
        }
    }

    /**
     * @throws ModelNotFoundException
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function approveGroup(Groups $groupRepo, Mailer $mailer): array
    {
        $groupView = $this->groupViews->findOrFail($this->request['id']);
        $groupRepo->query()->where('id', $groupView->id)->update(['pending' => 0]);

        $mailable = new GroupAcceptedEmail($groupView);

        $mailer->to($groupView->group_leader_email, $groupView->group_leaders)
            ->send($mailable);

        return api()->ok();
    }

    /**
     * @throws ModelNotFoundException
     */
    public function getDeleteModal(): string
    {
        $group = $this->findOrFailById();

        $message = view('mail.templates.delete-group', [
            'name' => $group->group_leaders,
            'group_name' => $group->name
        ]);

        return view('admin.group.partials.delete_modal', compact('group', 'message'));
    }

    public function deleteByValidation(Groups $groups, Mailer $mailer): array
    {
        try {
            $this->request->validate('message', 'subject', 'email', 'name');

            $group = $this->findOrFailById();

            $groups->delete($group);

            $mailable = app()->make(DefaultMailable::class)
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
     */
    private function findOrFailById(): ChurchGroupView
    {
        return $this->groupViews->findOrFail($this->request['id']);
    }
}
