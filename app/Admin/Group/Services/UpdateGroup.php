<?php

namespace App\Admin\Group\Services;

use App\Helpers\GroupHelper;
use App\Models\ChurchGroup;
use Framework\Exception\FileTypeNotAllowedException;
use Framework\Http\Message;
use Framework\Http\Request;
use Framework\Support\Arr;
use Framework\Support\Collection;

class UpdateGroup extends BaseGroupService
{
    private const ALLOWED_TAGS = ['a', 'h1', 'h2', 'h3', 'p', 'b', 'u', 'ul', 'ol', 'li', 'code', 'pre'];

    /**
     * @throws FileTypeNotAllowedException
     */
    public function update(ChurchGroup $group, Request|Collection|array $request, ?array $document = []): ChurchGroup
    {
        if (is_array($request)) {
            $request = collect($request);
        }

        $data = $request->except('id', 'tags', 'image', 'files')->all();

        $data['description'] = strip_tags($data['description'], self::ALLOWED_TAGS);
        $data['name'] = strip_tags($data['name']);
        $data['group_leaders'] = strip_tags($data['group_leaders']);
        $data['age_group'] = implode(',', (array) $data['age_group']);
        $data['on_days'] = implode(',', $data['on_days'] ?? []);
        $data['join_mode'] = Arr::get($data, 'join_mode') ?: null;
        $data['spiritual_movement_id'] = Arr::get($data, 'spiritual_movement_id') ?: null;
        $data['user_id'] = Arr::get($data, 'user_id') ?: null;

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

        $this->syncImages($group, [$request['image']]);

        if ($request['image']) {
            $data['image_url'] = GroupHelper::getPublicImagePath($group->id);
        }

        $this->repository->save($group, $data);

        $this->updateSearchEngine($group);

        Message::success('Sikeres mentés.');

        return $group;
    }
}
