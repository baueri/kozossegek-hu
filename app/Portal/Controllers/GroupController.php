<?php

namespace App\Portal\Controllers;

use App\Auth\Auth;
use App\Exception\EmailTakenException;
use App\Http\Responses\CreateGroupSteps\RegisterGroupForm;
use App\Http\Responses\PortalEditGroupForm;
use App\Portal\Services\GroupList;
use App\Portal\Services\PortalCreateGroup;
use App\Portal\Services\PortalUpdateGroup;
use App\Portal\Services\SendGroupContactMessage;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\GroupViews;
use App\QueryBuilders\Institutes;
use App\Services\GroupSearchRepository;
use Error;
use ErrorException;
use Exception;
use Framework\Exception\FileTypeNotAllowedException;
use Framework\Http\Exception\PageNotFoundException;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Http\View\Section;
use Framework\Model\Exceptions\ModelNotFoundException;
use Framework\Support\Arr;
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Throwable;

class GroupController extends PortalController
{
    public function kozossegek(Request $request, GroupList $service): string
    {
        $filter = $request->collect()->merge(['korosztaly' => $request['korosztaly']])->filter();

        return $service->getHtml($filter);
    }

    public function intezmenyKozossegek(Request $request, GroupList $service, Institutes $institutes): string
    {
        if (preg_match('/-(\d+)$/', $request['intezmeny'], $m)) {
            $institute = $institutes->findOrFail($m[1]);
        } else {
            $institute = $institutes->where('slug', "{$request['varos']}/{$request['intezmeny']}")->firstOrFail();
        }

        Section::set('templom_title', function () use ($institute) {
            $url = $institute->getMiserendUrl();
            $link = $url ? "<p><a href='$url' target='_blank'>{$url} <i class='fa fa-external-link-alt'></i></a></p>" : '';
            return <<<HTML
            <div class="text-center">
                <h2>$institute->name</h2>
                <h4>$institute->city, $institute->address</h4>
                $link
            </div>
            HTML;
        });

        return $service->getHtml(collect([
            'institute_id' => $institute->id
        ]));
    }

    public function groupsByCity(Request $request, GroupList $service): string
    {
        $data = [
            'varos' => trim($request->uri, '/')
        ];
        return $service->getHtml(collect($data));
    }

    public function kozossegRegisztracio(RegisterGroupForm $service): string
    {
        set_header_bg('/images/kozosseget_vezetek.jpg');
        return (string) $service;
    }

    /**
     * Közösség adatlap
     * @throws \Framework\Http\Exception\PageNotFoundException
     */
    public function kozosseg(Request $request, GroupViews $repo, Institutes $instituteRepo): string
    {
        use_default_header_bg();
        $backUrl = null;
        $user = Auth::user();
        $referer = (string) Arr::get($_SERVER, 'HTTP_REFERER');

        if (str_contains($referer, route('portal.groups'))) {
            $backUrl = $referer;
        }

        $slug = $request['kozosseg'];
        $group = $repo->with('tags')->bySlug($slug)->first();

        if (!$group || !$group->isVisibleBy($user)) {
            throw new PageNotFoundException();
        }

        $institute = $instituteRepo->find($group->institute_id);

        $similar_groups = GroupViews::query()->similarTo($group)->limit(4)->get();
        $slug = $group->slug();
        $keywords = builder('search_engine')->where('group_id', $group->getId())->first()['keywords'];

        if (!(new CrawlerDetect())->isCrawler()) {
            log_event('group_profile_opened', [
                'group_id' => $group->getId(),
                'referer' => $referer,
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
            ]);
        }

        return view('portal.kozosseg', compact(
            'group',
            'institute',
            'backUrl',
            'similar_groups',
            'slug',
            'keywords',
            'user',
        ));
    }

    /**
     * kapcsolatfelvételi űrlap html
     */
    public function groupContactForm(Request $request, ChurchGroups $repo): string
    {
        $slug = $request['kozosseg'];
        $group = $repo->bySlug($slug)->firstOrFail();

        return view('portal.partials.group-contact-form', compact('group'));
    }

    public function sendContactMessage(Request $request, GroupViews $repo, SendGroupContactMessage $service): array
    {
        try {
            $group = $repo->findOrFail($request['id']);
            $service->send($group, $request->map('strip_tags')->all());
            $msg = '<div class="alert alert-success text-center">Köszönjük! Üzenetedet elküldtük a közösségvezető(k)nek!</div>';
            return [
                'success' => true,
                'msg' => $msg
            ];
        } catch (Exception $e) {
            report($e);
            return ['success' => false];
        }
    }

    public function myGroups(GroupSearchRepository $groupRepo): string
    {
        $user = Auth::user();

        $groups = $groupRepo->getNotDeletedGroupsByUser($user);

        return view('portal.group.my_groups', compact('groups'));
    }

    public function editGroup(Request $request, GroupViews $groups, PortalEditGroupForm $response): string
    {
        $user = Auth::user();

        $group = $groups->find($request['id']);

        if (!$group || $group->isDeleted()) {
            raise_404();
        }

        if (!$group->isEditableBy($user)) {
            raise_403();
        }

        return $response($group);
    }

    public function createGroup(Request $request, PortalCreateGroup $createGroupService, RegisterGroupForm $form)
    {
        try {
            $user = Auth::user();
            $group =
                $createGroupService->createGroup(
                    $request->collect(),
                    $request->files['document'],
                    $user
                );

            if ($user) {
                Message::success('Közösség sikeresen létrehozva!');
                redirect_route('portal.edit_group', $group);
            }

            redirect_route('portal.group.create_group_success');

        } catch (FileTypeNotAllowedException $e) {
            Message::danger('Csak <b>word</b> és <b>pdf</b> dokumentumot fogadunk el igazolásként!');
            return (string) $form;
        } catch (EmailTakenException $e) {
            Message::danger('Ez az email cím már foglalt');
            return (string) $form;
        } catch (Exception | Error | Throwable | ErrorException $e) {
            process_error($e);
            Message::danger('Váratlan hiba történt a közösség létrehozásakor, kérjük próbáld meg később!');
            return (string) $form;
        }
    }

    public function updateMyGroup(Request $request, PortalUpdateGroup $service, ChurchGroups $groups)
    {
        $group = $groups->find($request['id']);
        if (!$group) {
            Message::danger('Nincs ilyen közösség!');
            redirect_route('portal.my_groups');
        }
        if (!$group->isEditableBy(Auth::user())) {
            raise_403();
        }
        try {
            $service->update($group, $request->only(
                'status',
                'name',
                'institute_id',
                'age_group',
                'occasion_frequency',
                'on_days',
                'spiritual_movement_id',
                'tags',
                'group_leaders',
                'description',
                'image',
                'join_mode'
            ), $request->files['document']);

            Message::success('Sikeres mentés!');
            redirect_route('portal.edit_group', $group);
        } catch (FileTypeNotAllowedException) {
            Message::danger(
                '<b>A dokumentum fájltípusa érvénytelen!</b> Az alábbi fájltípusokat fogadjuk el: doc, docx, pdf, jpeg, jpg, png'
            );
            redirect_route('portal.edit_group', $group);
        } catch (Error | Throwable $e) {
            report($e);
            Message::danger('Sikertelen mentés!');
            redirect_route('portal.edit_group', $group);
        }
    }

    /**
     * @throws ModelNotFoundException
     */
    public function deleteGroup(Request $request, ChurchGroups $groups)
    {
        $group = $groups->findOrFail($request['id']);

        if (!$group->isEditableBy(Auth::user())) {
            raise_403();
        }

        $groups->softDelete($group);

        Message::warning('Közösség törölve');

        redirect_route('portal.my_groups');
    }

    public function registrationSuccess(): string
    {
        return view('portal.group.create_group_success');
    }

    /**
     * @throws ModelNotFoundException
     */
    public function downloadDocument(Request $request, GroupViews $groups)
    {
        $group = $groups->findOrFail($request['id']);

        if (!$group->isEditableBy(Auth::user())) {
            raise_403();
        }

        $file_url = $group->getDocumentPath();
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");
        readfile($file_url);
    }
}
