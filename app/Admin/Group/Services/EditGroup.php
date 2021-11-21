<?php

namespace App\Admin\Group\Services;

class EditGroup extends BaseGroupForm
{
    protected function getAction($group): string
    {
        return route('admin.group.update', ['id' => $group->id]);
    }
}
