<?php

namespace App\Admin\Group\Services;

use App\Helpers\GroupHelper;
use App\Models\Group;
use Framework\Exception\FileTypeNotAllowedException;
use Framework\Support\Collection;

class CreateGroup extends BaseGroupService
{

    /**
     *
     * @param Collection $groupData
     * @param array|null $document
     * @return Group|null
     * @throws FileTypeNotAllowedException
     */
    public function create(Collection $groupData, ?array $document = null): ?Group
    {
        $data = $groupData->filter()->except('files', 'image', 'tags', 'institute')->all();
        $data['age_group'] = implode(',', $data['age_group'] ?? []);
        $data['on_days'] = implode(',', $data['on_days'] ?? []);
        $data['document'] = '';
        $data['image_url'] = '';

        if (!$this->validate($data)) {
            return null;
        }

        $instituteId = $groupData['institute_id'];

        if (($institute = $groupData['institute']) && !$instituteId) {
            $institute['approved'] = 0;
            $institute['user_id'] = $data['user_id'];
            $instituteId = $this->institutes->create($institute)->id;
            $data['institute_id'] = $instituteId;
        };

        if (!$instituteId) {
            $this->pushError('Nincs intézmény megadva');
            return null;
        }

        /* @var $group Group */
        $group = $this->repository->create($data);

        if ($group) {
            $this->syncTags($group, (array) $groupData['tags']);

            $this->updateSearchEngine($group);

            $this->syncImages($group, [$groupData['image']]);

            if ($groupData['image']) {
                $group->image_url = GroupHelper::getPublicImagePath($group->id);
            }

            $file = $this->uploadDocument($group, $document);

            if ($file) {
                $group->document = $file->getFileName();
            }

            $this->repository->save($group);
        }

        return $group;
    }
}
