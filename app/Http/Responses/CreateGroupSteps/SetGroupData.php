<?php
namespace App\Http\Responses\CreateGroupSteps;

use App\Auth\Auth;
use App\Models\GroupView;
use App\Models\Institute;
use App\Repositories\Institutes;
use App\Repositories\SpiritualMovements;
use Framework\Http\Request;
use Framework\Http\Session;

class SetGroupData extends AbstractGroupStep
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
        if ($this->request->isNotEmpty('next_step')) {
            Session::set(static::SESSION_KEY, $this->request->all());
        }
    }
    
    protected function getModel()
    {
        $request = $this->request;
        /* @var $institute Institute */
        $requestData = collect(Session::get(static::SESSION_KEY))->merge($request->all());
        $data = $requestData->only('occasion_frequency', 'institute_id', 'spiritual_movement_id', 'name', 'description');
        $institute = $this->institutes->find($data['institute_id']);
        $data['group_leaders'] = $requestData->get('group_leaders', $requestData['user_name']);
        $data['group_leader_email'] = $requestData->get('group_leader_email', $requestData['email']);
        $data['institute_name'] = $institute ? $institute->name : '';
        $data['city'] = $institute ? $institute->city : '';
        $data['district'] = $institute ? $institute->district : '';
        $data['age_group'] = implode(',', $requestData['age_group']);
        $data['on_days'] = implode(',', $requestData['on_days']);
        $data['spiritual_movement'] = $this->spiritualMovements->find($requestData['spiritual_movement_id'])['name'];

        $group = new GroupView($data);
        $user = Auth::user();

        $image = $requestData['image'];

        if (!$image && $institute && $institute->hasImage()) {
            $image = $institute->getImageRelPath();
        }

        return [
            'group' => $group,
            'user' => $user,
            'age_group_array' => $requestData['age_group'],
            'tags' => builder('tags')->get(),
            'image' => $image,
            'document' => $request->files['document']['name'],
            'group_tags' => $requestData['tags'],
            'group_days' => $requestData['on_days']
        ];
    }
    
    protected function getView()
    {
        return 'portal.group.create-steps.group-data';
    }
}
