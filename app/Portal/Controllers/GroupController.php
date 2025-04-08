<?php

namespace App\Portal\Controllers;

use App\Auth\Auth;
use App\Enums\GroupStatus;
use App\Exception\EmailTakenException;
use App\Helpers\HoneyPot;
use App\Http\Responses\CreateGroupSteps\RegisterGroupForm;
use App\Http\Responses\PortalEditGroupForm;
use App\Portal\Services\GroupList;
use App\Portal\Services\PortalCreateGroup;
use App\Portal\Services\PortalUpdateGroup;
use App\Portal\Services\SendGroupContactMessage;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\Cities;
use App\QueryBuilders\ChurchGroupViews;
use App\QueryBuilders\Institutes;
use App\QueryBuilders\UserTokens;
use App\Services\GroupSearchRepository;
use Error;
use ErrorException;
use Exception;
use Framework\Exception\FileTypeNotAllowedException;
use Framework\Http\Exception\PageNotFoundException;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\View\Section;
use Framework\Model\Exceptions\ModelNotFoundException;
use Framework\Support\Arr;
use InvalidArgumentException;
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use Throwable;

class GroupController extends PortalController
{
    public function kozossegek(Request $request, GroupList $service): string
    {
        $filter = $request->collect()->merge(['korosztaly' => $request['korosztaly']])->filter();
        $breadcrumb = null;
        if ($varos = $filter->get('varos')) {
            $breadcrumb = Cities::query()->where('name', $varos)->first()?->getBreadCrumb();
        }

        return $service->getHtml($filter, $breadcrumb);
    }

    public function intezmenyKozossegek(Request $request, GroupList $service, Institutes $institutes): string
    {
        if (preg_match('/-(\d+)$/', $request['intezmeny'], $m)) {
            $institute = $institutes->findOrFail($m[1]);
        } else {
            $institute = $institutes->where('slug', "{$request['varos']}/{$request['intezmeny']}")->firstOrFail();
        }

        Section::add('header', fn () => "<meta name='thumbnail' content='{$institute->getImageRelPath()}'/>");

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
        ]), $institute->getBreadCrumb());
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
    public function kozosseg(Request $request, ChurchGroupViews $repo, Institutes $instituteRepo): string
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

        $similar_groups = ChurchGroupViews::query()->similarTo($group)->limit(4)->get();
        $slug = $group->slug();
        $keywords = builder('search_engine')->where('group_id', $group->getId())->first()['keywords'] ?? '';

        if (!(new CrawlerDetect())->isCrawler()) {
            log_event('group_profile_opened', ['group_id' => $group->getId()]);
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

    public function sendContactMessage(Request $request, ChurchGroupViews $groups, SendGroupContactMessage $service): array
    {
        try {
            $group = $groups->findOrFail($request['id']);
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

    public function editGroup(Request $request, ChurchGroupViews $groups, PortalEditGroupForm $response): string
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

    public function createGroup(Request $request, PortalCreateGroup $createGroupService): array
    {
        Response::asJson();
        try {
            HoneyPot::validate('group-data', $request->get('website'));
            $user = Auth::user();
            $group =
                $createGroupService->createGroup(
                    $request->collect(),
                    $request->files['document'],
                    $user
                );

            if ($user) {
                Message::success('Közösség sikeresen létrehozva!');

                return ['success' => true, 'redirect' => route('portal.edit_group', $group)];
            }
            return ['success' => true, 'redirect' => route('portal.group.create_group_success')];
        } catch (FileTypeNotAllowedException $e) {
            return ['success' => false, 'message' => 'Csak <b>word</b> és <b>pdf</b> dokumentumot fogadunk el igazolásként!'];
        } catch (EmailTakenException $e) {
            return ['success' => false, 'message' => 'Ez az email cím már foglalt!'];
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
            $service->update($group, collect($request->only(
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
            )), $request->files['document']);

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
    public function downloadDocument(Request $request, ChurchGroupViews $groups)
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

    /**
     * @throws ModelNotFoundException
     */
    public function confirmGroup(UserTokens $tokens, ChurchGroups $groups): string
    {
        parse_str(base64_decode(request()->get('verify')), $decoded);
        $token = $tokens->getByToken($decoded['token'] ?? '');

        if (!$token) {
            return view('portal.error', ['message2' => 'Közösség megerősítése sikertelen! Hibás token.']);
        }

        if ($token->expired()) {
            return view('portal.error', ['message2' => 'Ennek a tokennek az érvényességi ideje lejárt!']);
        }

        $group = $groups->findOrFail($decoded['group_id']);
        if ((int) $token->data('group_id') !== (int) $group->getId()) {
            return view('portal.error', ['message2' => 'Közösség megerősítése sikertelen! Hibás token.']);
        }

        if ($decoded['action'] === 'confirm') {
            $groups->save($group, ['confirmed_at' => now(), 'notified_at' => null]);
            $view = view('portal.error', ['message2' => 'Közösség sikeresen megerősítve!']);
        } elseif ($decoded['action'] === 'deactivate') {
            $groups->save($group, ['status' => GroupStatus::inactive]);
            $view = view('portal.error', ['message2' => 'Közösséged inaktiválva lett. Közösséget bármikor újra aktiválhatsz belépés után a közösség adatlapján.']);
        } else {
            throw new InvalidArgumentException('Invalid confirm action');
        }

        $tokens->deleteModel($token);
        return $view;
    }
}
