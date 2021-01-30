<?php

namespace App\Portal\Controllers;

use App\Admin\Group\Services\UpdateGroup;
use App\Auth\Auth;
use App\Exception\EmailTakenException;
use App\Http\Responses\CreateGroupSteps\RegisterGroupForm;
use App\Http\Responses\PortalEditGroupForm;
use App\Models\Group;
use App\Models\GroupView;
use App\Portal\Services\GroupList;
use App\Portal\Services\PortalCreateGroup;
use App\Portal\Services\PortalUpdateGroup;
use App\Portal\Services\SendContactMessage;
use App\Repositories\Groups;
use App\Repositories\GroupViews;
use App\Repositories\Institutes;
use Error;
use ErrorException;
use Exception;
use Framework\Exception\FileTypeNotAllowedException;
use Framework\Http\Controller;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Model\ModelNotFoundException;
use Framework\Support\DataSet;
use Throwable;

/**
 * Description of GroupController
 *
 * @author ivan
 */
class GroupController extends Controller
{
    public function kozossegek(GroupList $service)
    {
        return $service->getHtml();
    }

    public function kozossegRegisztracio(RegisterGroupForm $service)
    {
        set_header_bg('/images/kozosseget_vezetek.jpg');
        return (string) $service;
    }

    /**
     * Közösség adatlap
     * @param Request $request
     * @param GroupViews $repo
     * @param Institutes $instituteRepo
     * @return string
     * @throws ModelNotFoundException
     */
    public function kozosseg(Request $request, GroupViews $repo, Institutes $instituteRepo)
    {
        use_default_header_bg();
        $backUrl = null;
        $user = Auth::user();

        if (strpos(DataSet::get($_SERVER, 'HTTP_REFERER'), route('portal.groups')) !== false) {
            $backUrl = $_SERVER['HTTP_REFERER'];
        }

        $slug = $request['kozosseg'];
        $group = $repo->findBySlug($slug);

        if (!$group || !$group->isVisibleBy($user)) {
            throw new ModelNotFoundException();
        }

        $institute = $instituteRepo->find($group->institute_id);

        $tag_names = builder('v_group_tags')->where('group_id', $group->id)->get();
        $similar_groups = $repo->findSimilarGroups($group, $tag_names)->all();
        $images = $group->getImages();
        $_SESSION['honepot_check_time'] = $checkTime = time();
        $_SESSION['honeypot_check_hash'] = $honeypot_check_hash = md5($checkTime);
        $slug = $group->slug();
        $keywords = builder('search_engine')->where('group_id', $group->id)->first()['keywords'];
        return view('portal.kozosseg', compact(
            'group',
            'institute',
            'backUrl',
            'tag_names',
            'similar_groups',
            'images',
            'honeypot_check_hash',
            'slug',
            'keywords',
            'user',
        ));
    }

    /**
     * kapcsolatfelvételi űrlap html
     * @param  Request    $request
     * @param  GroupViews $repo
     * @return string
     */
    public function groupContactForm(Request $request, GroupViews $repo)
    {
        $slug = $request['kozosseg'];
        $group = $repo->findBySlug($slug);

        return view('portal.partials.group-contact-form', compact('group'));
    }

    public function sendContactMessage(Request $request, Groups $repo, SendContactMessage $service)
    {
        try {
            $service->send($repo->findOrFail($request['id']), $request->all());

            return [
                'success' => true,
                'msg' => '<div class="alert alert-success">Köszönjük! Üzenetedet elküldtük a közösségvezető(k)nek!</div>'
            ];
        } catch (Exception $e) {
            return ['success' => false];
        }
    }

    public function myGroups(GroupViews $groupRepo)
    {
        $user = Auth::user();

        $groups = $groupRepo->getNotDeletedGroupsByUser($user);

        return view('portal.group.my_groups', compact('groups'));
    }

    public function editGroup(Request $request, GroupViews $groups, PortalEditGroupForm $response)
    {
        $user = Auth::user();

        /* @var $group GroupView */
        $group = $groups->find($request['id']);

        if (!$group || $group->isDeleted()) {
            raise_404();
        }

        if (!$group->isEditableBy($user)) {
            raise_500();
        }

        return $response->getResponse($group);
    }

    /**
     * @param Request $request
     * @param PortalCreateGroup $createGroupService
     * @param RegisterGroupForm $form
     * @return string|void
     */
    public function createGroup(Request $request, PortalCreateGroup $createGroupService, RegisterGroupForm $form)
    {
        try {
            $user = Auth::user();
            $group = db()->transaction(fn () =>
                $createGroupService->createGroup(
                    $request->collect(),
                    $request->files['document'],
                    $user
                ));

            if ($user) {
                Message::success('Közösség sikeresen létrehozva!');
                redirect_route('portal.edit_group', $group);
            } else {
                redirect_route('portal.group.create_group_success');
            }
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

    /**
     * @param Request $request
     * @param PortalUpdateGroup $service
     * @param Groups $groups
     */
    public function updateMyGroup(Request $request, PortalUpdateGroup $service, Groups $groups)
    {

        try {
            /* @var $group Group */
            $group = $groups->findOrFail($request['id']);
            $service->update($group, $request->only(
                'status',
                'name',
                'denomination',
                'institute_id',
                'age_group',
                'occasion_frequency',
                'on_days',
                'spiritual_movement',
                'tags',
                'group_leaders',
                'group_leader_phone',
                'group_leader_email',
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
            Message::danger('<b>A dokumentum fájltípusa érvénytelen!</b> Az alábbi fájltípusokat fogadjuk el: doc, docx, pdf');
            redirect_route('portal.edit_group', $group);
        } catch (Error | Throwable $e) {
            Message::danger('Sikertelen mentés!');
            redirect_route('portal.edit_group', $group);
        }
    }

    /**
     * @param Request $request
     * @param Groups $groups
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

    public function registrationSuccess()
    {
        return view('portal.group.create_group_success');
    }

    public function downloadDocument(Request $request, GroupViews $groups)
    {
        try {
            /* @var $group GroupView */
            $group = $groups->findOrFail($request['id']);

            if (!$group->isEditableBy(Auth::user())) {
                raise_403();
            }

            $file_url = $group->getDocumentPath();
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"" . basename($file_url) . "\"");
            readfile($file_url);
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
