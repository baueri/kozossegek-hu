<?php

namespace App\Portal\Controllers;

use App\Admin\Group\Services\UpdateGroup;
use App\Auth\Auth;
use App\Enums\DayEnum;
use App\Mail\GroupContactMail;
use App\Portal\Services\SearchGroupService;
use App\Repositories\AgeGroups;
use App\Repositories\Denominations;
use App\Repositories\Groups;
use App\Repositories\GroupStatusRepository;
use App\Repositories\GroupViews;
use App\Repositories\Institutes;
use App\Repositories\OccasionFrequencies;
use Framework\Exception\UnauthorizedException;
use Framework\Http\Controller;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Mail\Mailer;
use Framework\Model\ModelNotFoundException;
use \Exception;

/**
 * Description of GroupController
 *
 * @author ivan
 */
class GroupController extends Controller {

    public function kozossegek(Request $request, SearchGroupService $service,
            OccasionFrequencies $OccasionFrequencies, AgeGroups $AgeGroups)
    {
        $filter = $request->only('varos', 'search', 'korosztaly', 'rendszeresseg', 'tags');
        $filter['order_by'] = ['city', 'district'];
        $pg = $filter['page'] ?: 1;
        $groups = $service->search($filter);

        if (!$filter['varos']) {
            $groupsGrouped = $groups->groupBy('city');
        } else {
            $groupsGrouped = $groups->groupBy('district');
        }
        
        $model = [
            'groups' => $groups,
            'occasion_frequencies' => $OccasionFrequencies->all(),
            'age_groups' => $AgeGroups->all(),
            'page' => $groups->page(),
            'total' => $groups->total(),
            'perpage' => $groups->perpage(),
            'filter' => $filter,
            'selected_tags' => explode(',', $filter['tags']),
            'tags' => builder('tags')->get()
        ];

        return view('portal.kozossegek', $model);
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

        if (strpos($_SERVER['HTTP_REFERER'], route('portal.groups')) !== false) {
            $backUrl = $_SERVER['HTTP_REFERER'];
        }

        $slug = $request['kozosseg'];
        $group = $repo->findBySlug($slug);

        if (!$group) {
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

    public function sendContactMessage(Request $request, Groups $repo, Mailer $mailer)
    {
        $checkTime = $_SESSION['honepot_check_time'];
        $check_hash = $_SESSION['honeypot_check_hash'];

        if (!$checkTime || !$check_hash || time() - $checkTime < 5 || $request['website'] !== $check_hash) {
            throw new UnauthorizedException();
        }

        try {

            $group = $repo->findOrFail($request['id']);

            $mail = new GroupContactMail($request, $group);
            $mailer = $mailer->to($group->group_leader_email)->send($mail);

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
        if ($group) {
            $tags = builder('tags')->select('*')->get();
            $group_tags = collect(builder('group_tags')->whereGroupId($group->id)->get())->pluck('tag')->all();
            $spiritual_movements = db()->select('select * from spiritual_movements order by name');
            $occasion_frequencies = (new OccasionFrequencies)->all();
            $age_groups = (new AgeGroups)->all();
            $denominations = (new Denominations)->all();
            $age_group_array = array_filter(explode(',', $group->age_group));
            $statuses = (new GroupStatusRepository)->all();
            $images = $group->getImages();
            $days = DayEnum::all();
            $group_days = explode(',', $group->on_days);
        }

        return view('portal.my_group', compact('group', 'institute', 'denominations',
                'statuses', 'occasion_frequencies', 'age_groups', 'action', 'spiritual_movements', 'tags',
                'age_group_array', 'group_tags', 'days', 'group_days', 'images', 'group_tags'));
    }
    
    public function updateMyGroup(Request $request, UpdateGroup $service, \App\Repositories\GroupViews $groups)
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
        } catch (\Error $e) {
            Message::danger('Sikertelen mentés!');
        } finally {
            redirect_route('portal.my_group');
        }
    }

}
