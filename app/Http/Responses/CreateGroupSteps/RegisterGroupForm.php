<?php

namespace App\Http\Responses\CreateGroupSteps;

use App\Auth\Auth;
use App\Models\GroupView;
use App\Models\Institute;
use App\Repositories\Institutes;
use App\Repositories\SpiritualMovements;
use Framework\Http\Request;
use Framework\Http\Session;

class RegisterGroupForm extends AbstractGroupStep
{
    /**
     * @var Institutes
     */
    private Institutes $institutes;
    /**
     * @var SpiritualMovements
     */
    private SpiritualMovements $spiritualMovements;

    /**
     *
     * @param Request $request
     * @param Institutes $institutes
     * @param SpiritualMovements $spiritualMovements
     */
    public function __construct(Request $request, Institutes $institutes, SpiritualMovements $spiritualMovements)
    {
        parent::__construct($request);
        $this->institutes = $institutes;
        $this->spiritualMovements = $spiritualMovements;
    }

    protected function getModel()
    {
        $request = $this->request;
        /* @var $institute Institute */
        $data = $request->only(
            'occasion_frequency',
            'institute_id',
            'spiritual_movement_id',
            'name',
            'description',
            'join_mode'
        );
        $institute = $this->institutes->find($data['institute_id']);
        $data['group_leaders'] = $request->get('group_leaders', $request['user_name']);
        $data['group_leader_email'] = $request->get('group_leader_email', $request['email']);
        $data['institute_name'] = $institute ? $institute->name : '';
        $data['city'] = $institute ? $institute->city : '';
        $data['district'] = $institute ? $institute->district : '';
        $data['age_group'] = implode(',', $request['age_group']);
        $data['on_days'] = implode(',', $request['on_days']);
        $data['spiritual_movement'] = $this->spiritualMovements->find($request['spiritual_movement_id'])['name'];

        $group = new GroupView($data);
        $user = Auth::user();

        $image = $request['image'];

        if (!$image && $institute && $institute->hasImage()) {
            $image = $institute->getImageRelPath();
        }

        return [
            'group' => $group,
            'user' => $user,
            'age_group_array' => $request['age_group'],
            'tags' => builder('tags')->get(),
            'image' => $image,
            'document' => $request->files['document']['name'],
            'group_tags' => $request['tags'],
            'group_days' => $request['on_days'],
            'user_name' => $request['user_name'],
            'email' => $request['email'],
        ];
    }

    protected function getView()
    {
        return 'portal.group.create-steps.group-data';
    }
}
