<?php

namespace App\Admin\Group\Services;

use App\Models\Group;
use Framework\Support\Collection;

/**
 * Description of CreateGrooup
 *
 * @author ivan
 */
class CreateGroup extends BaseGroupService
{

    /**
     *
     * @param Collection $request
     * @param array|null $document
     * @return Group|null
     */
    public function create(Collection $request, ?array $document = null): ?Group
    {
        $data = $request->except('files', 'image', 'tags', 'institute')->all();

        $data['age_group'] = implode(',', $data['age_group'] ?? []);
        $data['on_days'] = implode(',', $data['on_days'] ?? []);

        if (!$this->validate($data)) {
            return null;
        }

        $instituteId = null;

        if ($institute = $request['institute']) {
            $institute['approved'] = 0;
            $institute['user_id'] = $data['user_id'];
            $instituteId = $this->institutes->create($institute)->id;
            $data['institute_id'] = $instituteId;
        };

        if (!$request['institute_id'] && !$instituteId) {
            $this->pushError('Nincs intézmény megadva');
            return null;
        }

        /* @var $group Group */
        $group = $this->repository->create($data);

        if ($group) {
            $this->syncTags($group, (array) $request['tags']);

            $this->updateSearchEngine($group);

            $this->syncImages($group, [$request['image']]);

            $file = $this->uploadDocument($group, $document);

            if ($file) {
                $group->document = $file->getFileName();
            }

            $this->repository->save($group);
        }

        return $group;
    }
}
