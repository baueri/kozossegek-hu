<?php

namespace App\Admin\Group\Services;

use App\Models\ChurchGroupView;
use App\QueryBuilders\Users;

class ValidateGroupForm
{
    public function render(ChurchGroupView $group): string
    {
        $user = app()->make(Users::class)->find($group->user_id);

        $model = [
            'group' => $group,
            'selected_tags' => $group->tags->map->translate()->implode(', '),
            'image' => $group->getThumbnail(),
            'document' => $group->getDocumentUrl(),
            'has_document' => $group->hasDocument(),
            'user' => $user
        ];
        return view('admin.group.validate', $model);
    }
}
