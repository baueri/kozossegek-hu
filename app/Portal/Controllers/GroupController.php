<?php

namespace App\Portal\Controllers;

use App\Admin\Group\Services\CreateGroup;
use App\Admin\Group\Services\UpdateGroup;
use App\Auth\Auth;
use App\Factories\CreateGroupStepFactory;
use App\Helpers\GroupHelper;
use App\Http\Responses\CreateGroupSteps\AbstractGroupStep;
use App\Http\Responses\PortalEditGroupForm;
use App\Models\Group;
use App\Models\GroupView;
use App\Portal\Services\CreateUser;
use App\Portal\Services\GroupList;
use App\Portal\Services\PortalCreateGroup;
use App\Portal\Services\SendContactMessage;
use App\Repositories\Groups;
use App\Repositories\GroupViews;
use App\Repositories\Institutes;
use App\Services\CreateUserFromGroup;
use Error;
use ErrorException;
use Exception;
use Framework\File\File;
use Framework\Http\Controller;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Http\Session;
use Framework\Model\ModelNotFoundException;
use Phinx\Console\Command\Create;
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

    public function kozossegRegisztracio(Request $request, CreateGroupStepFactory $factory)
    {
        $user = Auth::user();
        $step = $request['next_step'] ?: 'login';

        $data = array_merge(Session::get(AbstractGroupStep::SESSION_KEY, []), $request->all());

        Session::set(AbstractGroupStep::SESSION_KEY, $data);

        if ($user) {
            if ($step === 'login') {
                $step = 'group_data';
            }

            $steps = [
                1 => ['group_data', 'Közösség adatainak megadása'],
                ['finish_registration', 'Regisztráció befejezése']
            ];
        } else {
            $steps = [
                1 => ['login', 'Közösség adatainak megadása'],
                ['group_data', 'Regisztráció befejezése'],
                ['finish_registration', 'Regisztráció befejezése']
            ];
        }

        $service = $factory->getGroupStep($step);

        return (string) $service->render(compact('steps', 'step'));
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
        $backUrl = null;
        $user = Auth::user();

        if (strpos($_SERVER['HTTP_REFERER'], route('portal.groups')) !== false) {
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
        $metaKeywords = builder('search_engine')->where('group_id', $group->id)->first()['keywords'];

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
            'user'
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
     */
    public function createGroup(Request $request, PortalCreateGroup $createGroupService)
    {
        try {
            $group = $createGroupService->createGroup(
                collect(Session::get(AbstractGroupStep::SESSION_KEY)),
                $request->files['document'],
                $user = Auth::user()
            );

            if ($group) {
                Session::forget(AbstractGroupStep::SESSION_KEY);
                if ($user) {
                    Message::success('Közösség sikeresen létrehozva!');
                    redirect_route('portal.edit_group', $group);
                } else {
                    redirect_route('portal.group.create_group_success');
                }
            } else {
                Message::danger('Kérjük ellenőrizd az adataidat!');
                redirect_route('portal.register_group');
            }

        } catch (Exception | Error | Throwable | ErrorException $ex) {
            Message::danger('Váratlan hiba történt a közösség létrehozásakor, kérjük próbáld meg később!');
            redirect_route('portal.register_group');
        }
    }

    public function updateMyGroup(Request $request, UpdateGroup $service, GroupViews $groups)
    {
        $group = $groups->findOrFail($request['id']);

        try {
            $service->update($group->id, $request->only(
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
                'image'
            ));

            Message::success('Sikeres mentés!');
            redirect_route('portal.edit_group', $group);
        } catch (ModelNotFoundException $e) {
            Message::danger('Nincs ilyen közösség!');
            redirect_route('portal.my_groups');
        } catch (Error $e) {
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
}
