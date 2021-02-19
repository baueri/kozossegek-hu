<?php

namespace App\Admin\Group\Services;

use App\Models\GroupView;

class ValidateGroupForm
{
    public function render(GroupView $group)
    {
        $tags = builder('v_group_tags')
            ->where('group_id', $group->id)
            ->select('GROUP_CONCAT(tag_name SEPARATOR ", ") as names')
            ->first();

        $model = [
            'group' => $group,
            'selected_tags' => $tags['names'],
            'image' => $group->getThumbnail(),
            'document' => $group->getDocumentUrl(),
            'has_document' => $group->hasDocument()
        ];

        return view('admin.group.validate', $model);
    }
}
