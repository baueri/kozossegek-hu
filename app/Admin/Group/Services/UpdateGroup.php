<?php

namespace App\Admin\Group\Services;

use App\Models\Group;
use Framework\Exception\FileTypeNotAllowedException;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Model\ModelNotFoundException;
use Framework\Support\Collection;

/**
 * Description of UpdateGroup
 *
 * @author ivan
 */
class UpdateGroup extends BaseGroupService
{

    /**
     *
     * @param int $id
     * @param Request|Collection|array $request
     * @param array|null $document
     * @throws ModelNotFoundException|FileTypeNotAllowedException
     */
    public function update(int $id, $request, ?array $document = [])
    {
        if (is_array($request)) {
            $request = collect($request);
        }

        /* @var $group Group */
        $group = $this->repository->findOrFail($id);

        $data = $request->except('id', 'tags')->all();

        $data['description'] = strip_tags($data['description'], ['a', 'h1', 'h2', 'h3', 'p', 'b', 'u', 'ul', 'ol', 'li', 'code', 'pre']);

        $data['name'] = strip_tags($data['name']);
        $data['group_leaders'] = strip_tags($data['group_leaders']);
        $data['group_leader_email'] = strip_tags($data['group_leader_email']);
        $data['group_leader_phone'] = strip_tags($data['group_leader_phone']);
        $data['age_group'] = implode(',', $data['age_group']);
        $data['on_days'] = implode(',', $data['on_days'] ?? []);

        if (!$this->validate($data)) {
            Message::danger('A csillaggal jelölt mezők kitöltése kötelező!');
            if (is_admin()) {
                redirect_route('admin.group.edit', $group);
            } else {
                redirect_route('portal.edit_group', $group);
            }
        }

        $file = $this->uploadDocument($group, $document);

        if ($file) {
            $data['document'] = $file->getFileName();
        } else {
            $data['document'] = $group->document;
        }

        $this->syncTags($group, (array) $request['tags']);

        $group->update($data);

        $this->repository->update($group);

        $this->updateSearchEngine($group);

        $this->syncImages($group, [$request['image']]);

        Message::success('Sikeres mentés.');
    }
}
