<?php

namespace App\Portal\Controllers;
use App\Portal\Services\SearchGroupService;
use App\Repositories\AgeGroups;
use App\Repositories\Institutes;
use App\Repositories\GroupViews;
use App\Repositories\Groups;
use App\Repositories\OccasionFrequencies;
use Framework\Http\Controller;
use Framework\Http\Request;
use Framework\Model\ModelNotFoundException;
use Framework\Mail\Mailer;
use App\Mail\GroupContactMail;

/**
 * Description of GroupController
 *
 * @author ivan
 */
class GroupController extends Controller {

    public function kozossegek(Request $request, SearchGroupService $service,
            OccasionFrequencies $OccasionFrequencies, AgeGroups $AgeGroups)
    {
        $filter = $request->all();
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

        return view('portal.kozosseg', compact('group', 'institute', 'backUrl', 'tag_names',
            'similar_groups', 'images', 'honeypot_check_hash', 'slug'));
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
            throw new \Framework\Exception\UnauthorizedException();
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
        $user = \App\Auth\Auth::user();
        $group = $groups->getGroupByUser($user);
        
        dd($group);
    }

}
