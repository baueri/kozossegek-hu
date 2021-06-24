<?php

namespace App\Admin\Group\Services;

use App\Models\GroupView;
use App\Repositories\Users;

class ValidateGroupForm
{
    public function render(GroupView $group)
    {
        $tags = builder('v_group_tags')
            ->where('group_id', $group->id)
            ->select('GROUP_CONCAT(tag_name SEPARATOR ", ") as names')
            ->first();

        $user = app(Users::class)->find($group->user_id);

        $model = [
            'group' => $group,
            'selected_tags' => $tags['names'],
            'image' => $group->getThumbnail(),
            'document' => $group->getDocumentUrl(),
            'has_document' => $group->hasDocument(),
            'user' => $user
        ];

        return view('admin.group.validate', $model);
    }
}
