<?php

namespace App\Http\Responses\CreateGroupSteps;

use App\Admin\Group\Services\BaseGroupService;
use App\Auth\Auth;
use App\Models\ChurchGroupView;
use App\QueryBuilders\Institutes;
use App\QueryBuilders\SpiritualMovements;
use Framework\Http\Request;

class RegisterGroupForm extends AbstractGroupStep
{
    private Institutes $institutes;

    private SpiritualMovements $spiritualMovements;

    public function __construct(Request $request, Institutes $institutes, SpiritualMovements $spiritualMovements)
    {
        parent::__construct($request);
        $this->institutes = $institutes;
        $this->spiritualMovements = $spiritualMovements;
    }

    protected function getModel(): array
    {
        $request = $this->request;
        $data = collect($request->only(
            'occasion_frequency',
            'institute_id',
            'spiritual_movement_id',
            'name',
            'join_mode'
        ))->map('strip_tags');

        $data['description'] = strip_tags(
            (string) $request->get('description'),
            BaseGroupService::ALLOWED_TAGS
        );

        $institute = $this->institutes->find($data['institute_id']);

        $data['group_leaders'] = strip_tags((string) $request->get('group_leaders', $request['user_name']));
        $data['institute_name'] = $institute ? $institute->name : '';
        $data['city'] = $institute ? $institute->city : '';
        $data['district'] = $institute ? $institute->district : '';
        $data['age_group'] = strip_tags(implode(',', $request['age_group'] ?? []));
        $data['on_days'] = strip_tags(implode(',', $request['on_days'] ?? []));
        $data['spiritual_movement'] = '';
        if (
            $request['spiritual_movement_id'] &&
            $movement = $this->spiritualMovements->find($request['spiritual_movement_id'])
        ) {
            $data['spiritual_movement'] = $movement->name;
        }

        $group = new ChurchGroupView($data->all());
        $user = Auth::user();

        $image = $request['image'];

        if (!$image && $institute && $institute->hasImage()) {
            $image = $institute->getImageRelPath();
        }

        return [
            'group' => $group,
            'user' => $user,
            'age_group_array' => $request['age_group'] ?? [],
            'tags' => builder('tags')->get(),
            'image' => $image,
            'document' => $request->files['document']['name'] ?? null,
            'group_tags' => $request['tags'] ?? [],
            'group_days' => $group->getDays(),
            'user_name' => $request['user_name'],
            'phone_number' => $user ? $user->phone_number : $request['phone_number'],
            'email' => $user ? $user->email : $request['email']
        ];
    }

    protected function getView(): string
    {
        return 'portal.group.create-steps.group-data';
    }
}
