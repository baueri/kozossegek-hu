<?php
namespace App\Http\Responses\CreateGroupSteps;


class SetGroupData extends AbstractGroupStep {

    /**
     * @var \App\Repositories\Institutes
     */
    private $institutes;

    /**
     * 
     * @param \Framework\Http\Request $request
     * @param \App\Repositories\Institutes $institutes
     */
    public function __construct(\Framework\Http\Request $request, \App\Repositories\Institutes $institutes) {
        parent::__construct($request);
        $this->institutes = $institutes;
    }
    
    protected function getModel() {
        $institute = $this->institutes->find($this->request['institute_id']);
        $data = [
            'group_leaders' => $this->request->get('group_leaders', $this->request['name']),
            'group_leader_email' => $this->request->get('group_leader_email', $this->request['email']),
            'occasion_frequency' => $this->request['occasion_frequency'],
            'institute_id' => $this->request['institute_id'],
            'institute_name' => $institute ? $institute->name : '',
            'city' => $institute ? $institute->city : '',
        ];
        $group = new \App\Models\GroupView($data);
        $user = \App\Auth\Auth::user();
        return [
            'group' => $group,
            'user' => $user,
            'age_group_array' => $this->request['age_group']
        ];
    }
    
    protected function getView() {
        return 'portal.group.create-steps.group-data';
    }

}
