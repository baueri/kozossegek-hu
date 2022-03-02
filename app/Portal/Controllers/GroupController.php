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
use App\QueryBuilders\GroupViews;
use App\Repositories\Groups;
use App\Services\GroupSearchRepository;
use Error;
use ErrorException;
use Exception;
use Framework\Exception\FileTypeNotAllowedException;
use Framework\Http\Exception\PageNotFoundException;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Model\ModelNotFoundException;
use Framework\Support\Arr;
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Legacy\Group;
use Legacy\Institutes;
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
        $city = str_replace('-', ' ', $request['varos']);
        $instituteName = str_replace('-', ' ', $request['intezmeny']);
        $institute = $institutes->searchByCityAndInstituteName($city, $instituteName);

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
    public function kozosseg(Request $request, GroupSearchRepository $repo, Institutes $instituteRepo): string
    {
        use_default_header_bg();
        $backUrl = null;
        $user = Auth::user();
        $referer = (string) Arr::get($_SERVER, 'HTTP_REFERER');

        if (str_contains($referer, route('portal.groups'))) {
            $backUrl = $referer;
        }

        $slug = $request['kozosseg'];
        $group = $repo->findBySlug($slug);

        if (!$group || !$group->isVisibleBy($user)) {
            throw new PageNotFoundException();
        }

        $institute = $instituteRepo->find($group->institute_id);

        $tag_names = builder('v_group_tags')->where('group_id', $group->getId())->get();
        $similar_groups = $repo->findSimilarGroups($group, $tag_names)->all();
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
            'tag_names',
            'similar_groups',
            'slug',
            'keywords',
            'user',
        ));
    }

    /**
     * kapcsolatfelvételi űrlap html
     */
    public function groupContactForm(Request $request, GroupSearchRepository $repo): string
    {
        $slug = $request['kozosseg'];
        $group = $repo->findBySlug($slug);

        return view('portal.partials.group-contact-form', compact('group'));
    }

    public function sendContactMessage(Request $request, GroupViews $repo, SendGroupContactMessage $service): array
    {
        try {
            $group = $repo->findOrFail($request['id']);
            $service->send($group, $request->map('strip_tags', true)->all());
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
            raise_500();
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

    public function updateMyGroup(Request $request, PortalUpdateGroup $service, Groups $groups)
    {
        try {
            /* @var $group Group */
            $group = $groups->findOrFail($request['id']);
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
        } catch (ModelNotFoundException $e) {
            Message::danger('Nincs ilyen közösség!');
            redirect_route('portal.my_groups');
        } catch (FileTypeNotAllowedException $e) {
            Message::danger(
                '<b>A dokumentum fájltípusa érvénytelen!</b> Az alábbi fájltípusokat fogadjuk el: doc, docx, pdf, jpeg, jpg, png'
            );
            redirect_route('portal.edit_group', $group);
        } catch (Error | Throwable $e) {
            dd($e);
            Message::danger('Sikertelen mentés!');
            redirect_route('portal.edit_group', $group);
        }
    }

    /**
     * @throws ModelNotFoundException
     */
    public function deleteGroup(Request $request, Groups $groups)
    {
        /* @var $group Group */
        $group = $groups->findOrFail($request['id']);

        if (!$group->isEditableBy(Auth::user())) {
            raise_500();
        }

        $groups->delete($group);

        Message::warning('Közösség törölve');

        redirect_route('portal.my_groups');
    }

    public function registrationSuccess(): string
    {
        return view('portal.group.create_group_success');
    }

    /**
     * @throws \Framework\Model\ModelNotFoundException
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
