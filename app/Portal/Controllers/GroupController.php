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
class GroupController extends Controller {

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
        
        return view('portal.kozosseg', compact('group', 'institute', 'backUrl', 'tag_names',
            'similar_groups', 'images', 'honeypot_check_hash', 'slug', 'keywords'));
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
        } catch(Exception $e) {
            return ['success' => false];
        }
    }
    
    public function myGroup(GroupViews $groups)
    {
        $user = Auth::user();
        $group = $groups->getGroupByUser($user);
        $statuses = (new GroupStatusRepository)->all();
        $tags = builder('tags')->select('*')->get();
        $spiritual_movements = db()->select('select * from spiritual_movements order by name');
        $occasion_frequencies = (new OccasionFrequencies)->all();
        $age_groups = (new AgeGroups)->all();
        $denominations = (new Denominations)->all();
        $days = DayEnum::all();
        
        if ($group) {
            $group_tags = collect(builder('group_tags')->whereGroupId($group->id)->get())->pluck('tag')->all();
            $age_group_array = array_filter(explode(',', $group->age_group));
            $images = $group->getImages();
            $group_days = explode(',', $group->on_days);
            $view = 'portal.group.edit_my_group';
            $action = route('portal.my_group.update');
        } else {
            $group = new GroupView([
                'group_leaders' => $user->name,
                'group_leader_email' => $user->email
            ]);
            $view = 'portal.group.create_my_group';
            $action = route('portal.my_group.create');
        }
        
        return view($view, compact('group', 'institute', 'denominations',
                'statuses', 'occasion_frequencies', 'age_groups', 'action', 'spiritual_movements', 'tags',
                'age_group_array', 'group_tags', 'days', 'group_days', 'images', 'group_tags'));
    }
    
    public function createMyGroup(Request $request, CreateGroup $service, Groups $groups)
    {
        try {
            $data = $request->only(
                'status', 'name', 'denomination', 'institute_id', 'age_group', 'occasion_frequency',
                    'on_days', 'spiritual_movement', 'tags', 'group_leaders', 'group_leader_phone', 'group_leader_email',
                    'description', 'image'
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
        try {
            
            $group = $groups->getGroupByUser(Auth::user());
            $service->update($group->id, $request->only(
                'status', 'name', 'denomination', 'institute_id', 'age_group', 'occasion_frequency',
                    'on_days', 'spiritual_movement', 'tags', 'group_leaders', 'group_leader_phone', 'group_leader_email',
                    'description', 'image'
            ));
             
            Message::success('Sikeres mentés!');
            
        } catch(ModelNotFoundException $e) {
            Message::danger('Nincs ilyen közösség!');
        } catch (Error $e) {
            Message::danger('Sikertelen mentés!');
        } finally {
            redirect_route('portal.my_group');
        }
    }

}
