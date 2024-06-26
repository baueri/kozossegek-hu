<?php

namespace App\Admin\Controllers;

use App\Admin\Group\Services\BaseGroupForm;
use App\Admin\Group\Services\CreateGroup;
use App\Admin\Group\Services\EditGroup;
use App\Admin\Group\Services\ListGroups;
use App\Admin\Group\Services\UpdateGroup;
use App\Admin\Group\Services\ValidateGroupForm;
use App\Auth\Auth;
use App\Helpers\GroupHelper;
use App\Mail\DefaultMailable;
use App\Mail\GroupAcceptedEmail;
use App\Models\ChurchGroupView;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\ChurchGroupViews;
use App\Services\RebuildSearchEngine;
use Exception;
use Framework\Exception\FileTypeNotAllowedException;
use Framework\Http\Exception\RequestParameterException;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Mail\Mailer;
use Framework\Model\Exceptions\ModelNotFoundException;
use RuntimeException;
use Throwable;

class GroupController extends AdminController
{
    public function __construct(Request $request, private ChurchGroupViews $groupViews)
    {
        parent::__construct($request);
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
            $group = $service->create($this->request->collect()->except('_token'));
            event_logger()->logEvent('group_created', ['group_id' => $group->getId()]);

            Message::success('Közösség létrehozva.');
            redirect_route('admin.group.edit', ['id' => $group->getId()]);
        } catch (Exception $e) {
            process_error($e);
            Message::danger('Váratlan hiba történt!');
            return $form->render(ChurchGroupView::make($this->request->all()));
        }
    }

    /**
     * @throws ModelNotFoundException
     */
    public function edit(EditGroup $service): string
    {
        return $service->render($this->findOrFailById());
    }

    /**
     * @throws FileTypeNotAllowedException
     * @throws ModelNotFoundException
     */
    public function update(UpdateGroup $service, ChurchGroups $groups): void
    {
        $group = $groups->findOrFail($this->request['id']);
        $oldTags = $group->tags->pluck('tag')->sort()->implode(',');
        
        $service->update($group, $this->request->collect());

        $newTags = $this->request->collect('tags')->sort()->implode(',');
        $tagDiff = $oldTags !== $newTags ? ['tags' => ['old' => $oldTags, 'new' => $newTags]] : [];

        log_event('group_updated', ['group_id' => $group->getId(), 'diff' => array_merge($group->diff(), $tagDiff)], Auth::user());

        redirect_route('admin.group.edit', ['id' => $this->request['id']]);
    }

    /**
     * @throws ModelNotFoundException
     */
    public function delete(ChurchGroups $repository): void
    {
        $group = $repository->findOrFail($this->request['id']);

        $repository->softDelete($group);

        Message::warning('Közösség lomtárba helyezve.');

        redirect($this->request->referer());
    }

    public function destroy(ChurchGroups $repository): void
    {
        $groupId = $this->request['id'];
        $group = $repository->findOrFail($groupId);

        $repository->hardDeleteModel($group);

        rrmdir(GroupHelper::getStoragePath($groupId));

        Message::warning('Közösség törölve.');

        redirect($this->request->referer());
    }

    public function trash(ListGroups $service): string
    {
        return $service->show();
    }

    public function emptyTrash(): never
    {
        ChurchGroups::query()->trashed()->hardDelete();

        Message::warning('Lomtár kiürítve.');

        redirect($this->request->referer());
    }

    public function rebuildSearchEngine(RebuildSearchEngine $service): void
    {
        $service->run();

        Message::success('Sikeres keresőmotor frissítés');

        redirect_route('admin.group.list');
    }

    public function restore(ChurchGroups $groups): void
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

            log_event('group_rejected', ['group_id' => $group->getId(), 'message' => $this->request['message']], Auth::user());

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
    public function approveGroup(ChurchGroups $groupRepo, Mailer $mailer): array
    {
        $groupView = $this->groupViews->findOrFail($this->request['id']);

        if (!$groupView->manager->activated_at) {
            throw new RuntimeException('Nem megerősített fiók közösségének jóváhagyása nem megengedett');
        }

        $groupRepo->query()->where('id', $groupView->id)->update(['pending' => 0]);

        $mailable = new GroupAcceptedEmail($groupView);

        $mailer->to($groupView->group_leader_email, $groupView->group_leaders)
            ->send($mailable);

        log_event('group_approved', ['group_id' => $groupView->getId()], Auth::user());


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

    public function deleteByValidation(ChurchGroups $groups, Mailer $mailer): array
    {
        try {
            $this->request->validate('message', 'subject', 'email', 'name');

            $group = $this->findOrFailById();

            $groups->deleteModel($group);

            $mailable = app()->make(DefaultMailable::class)
                ->setMessage($this->request['message'])
                ->subject("kozossegek.hu - {$this->request['subject']}");

            $mailer->to($this->request['email'], $this->request['name'])->send($mailable);

            log_event('group_denied', ['group_id' => $group->getId(), 'message' => $this->request['message']], Auth::user());

            return api()->ok();
        } catch (RequestParameterException) {
            return api()->error('Minden mező kötelező!');
        } catch (Exception) {
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
