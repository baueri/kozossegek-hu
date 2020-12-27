<?php

namespace App\Admin\Group\Services;

use App\Models\Group;
use Framework\File\File;
use Framework\Http\Request;
use App\Http\Exception\RequestParameterException;
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
        $data = $request->except('files', 'image', 'tags')->all();

        $data['age_group'] = implode(',', $data['age_group'] ?? []);

        if (!$this->validate($data)) {
            return null;
        }

        /* @var $group Group */
        $group = $this->repository->create($data);

        if ($group) {
            $this->syncTags($group, $request['tags']);

            $this->updateSearchEngine($group);

            $this->syncImages($group, [$request['image']]);

            $file = $this->uploadDocument($group, $document);

            $group->document = $file->getFileName();

            $this->repository->save($group);
        }

        return $group;
    }
}
