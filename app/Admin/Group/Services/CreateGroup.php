<?php

namespace App\Admin\Group\Services;

use App\Models\Group;
use Framework\Http\Message;
use Framework\Http\Request;

/**
 * Description of CreateGrooup
 *
 * @author ivan
 */
class CreateGroup extends BaseGroupService {


    /**
     * 
     * @param Request $request
     * @return Group
     */
    public function create($request)
    {
        $data = $request->except('files', 'image')->all();
        
        $tags = $data['tags'];
        $data['age_group'] = implode(',', $data['age_group']);
        $data['tags'] = implode(',', $tags);

        $group = $this->repository->create($data);
        
        $this->updateSearchEngine($group);
        
        $this->syncImages($group, [$request['image']]);

        Message::success('Közösség létrehozva.');

        return $group;
    }
}
