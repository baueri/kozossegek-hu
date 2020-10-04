<?php

namespace App\Admin\Group\Services;

use App\Models\Group;

/**
 * Description of EditGroup
 *
 * @author ivan
 */
class EditGroup extends BaseGroupForm {

    protected function getGroup(): Group {
        return $this->repository->findOrFail($this->request['id']);
    }

    protected function getAction(Group $group) {
        return route('admin.group.update', ['id' => $group->id]);
    }

}
