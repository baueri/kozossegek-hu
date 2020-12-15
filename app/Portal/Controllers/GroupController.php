<?php

namespace App\Portal\Controllers;

use App\Admin\Group\Services\CreateGroup;
use App\Admin\Group\Services\UpdateGroup;
use App\Auth\Auth;
use App\Enums\DayEnum;
use App\Models\GroupView;
use App\Repositories\AgeGroups;
use App\Repositories\Denominations;
use App\Repositories\Groups;
use App\Repositories\GroupStatusRepository;
use App\Repositories\GroupViews;
use App\Repositories\Institutes;
use App\Repositories\OccasionFrequencies;
use Error;
use Exception;
use Framework\Http\Controller;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Model\ModelNotFoundException;

/**
 * Description of GroupController
 *
 * @author ivan
 */
class GroupController extends Controller
{
    public function kozossegek(\App\Portal\Services\GroupList $service)
    {
        return $service->getHtml();
    }

    public function kozossegRegisztracio(Request $request)
    {
        $step = $request['next_step'] ?: 1;
        switch ($step) {
            case 1:
            default:
                return app()->make(\App\Http\Responses\CreateGroupSteps\LoginOrRegister::class);
            case 2:
                return app()->make(\App\Http\Responses\CreateGroupSteps\SetGroupData::class);
            case 3:
                return app()->make(\App\Http\Responses\CreateGroupSteps\UploadDocument::class);
        }
    }

    /**
     * Közösség adatlap
     * @param  Request    $request
     * @param  GroupViews $repo
     * @param  Institutes $instituteRepo
     * @return string
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

    public function sendContactMessage(Request $request, Groups $repo, \App\Portal\Services\SendContactMessage $service)
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
        
        $groups = $groupRepo->getGroupsByUser($user);
        
        return view('portal.group.my_groups', compact('groups'));
    }
    
    public function myGroup(Request $request, GroupViews $groups, \App\Http\Responses\PortalEditGroupForm $response)
    {
        $user = Auth::user();
        
        /* @var $group GroupView */
        $group = $groups->find($request['id']);
        
        if (!\App\Helpers\GroupHelper::isGroupEditableBy($group, $user)) {
            
        }
        
        return $response->getResponse($group);
    }
    
    public function createMyGroup(Request $request, CreateGroup $service, Groups $groups)
    {
        try {
            $data = $request->only(
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
            );
            
            $data['user_id'] = Auth::user()->id;
            
            $service->create(collect($data));
            
            Message::success('Közösség sikeresen létrehozva!<br>Mielőtt még láthatóvá tennénk a közösségedet, átnézzük, hogy minden adatot rendben találunk-e. Köszönjük a türelmet!');
            
            redirect_route('portal.my_group');
        } catch (\Exception|\Error|\Throwable| \ErrorException $ex) {
            Message::danger('Közösség létrehozása nem sikerült, kérjük próbáld meg később!');
            dd($ex);
        }
    }
    
    public function updateMyGroup(Request $request, UpdateGroup $service, GroupViews $groups)
    {
        $group = $groups->findOrFail($request['id']);
        
        try {
            $user = Auth::user();
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
            redirect_route('portal.my_group', $group);
        } catch (ModelNotFoundException $e) {
            Message::danger('Nincs ilyen közösség!');
            redirect_route('portal.my_groups');
        } catch (Error $e) {
            Message::danger('Sikertelen mentés!');
            redirect_route('portal.my_group', $group);
        }
    }
}
