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
     * @param Collection|array $request
     * @param File|null $document
     * @return Group|null
     * @throws RequestParameterException
     */
    public function create($request, ?File $document = null): ?Group
    {
        $data = $request->except('files', 'image', 'tags')->all();
        
        $data['age_group'] = implode(',', $data['age_group'] ?? []);
        
        if (!$this->validate($data)) {
            throw new RequestParameterException('A csillaggal jelölt mezők kitöltése kötelező!');
        }
        
        $group = $this->repository->create($data);

        if ($group) {

            $this->syncTags($group, $request['tags']);

            $this->updateSearchEngine($group);

            $this->syncImages($group, [$request['image']]);

            $this->uploadDocument($group, $document);
        }

        return $group;
    }
}
