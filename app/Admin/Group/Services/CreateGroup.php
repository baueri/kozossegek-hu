<?php

namespace App\Admin\Group\Services;

/**
 * Description of CreateGrooup
 *
 * @author ivan
 */
class CreateGroup extends BaseGroupService {


    public function create(array $data)
    {
        $tags = $data['tags'];
        $data['age_group'] = implode(',', $data['age_group']);
        $data['tags'] = implode(',', $tags);

        $group = $this->repository->create($data);
        
        $this->updateSearchEngine($group);

        \Framework\Http\Message::success('Közösség létrehozva.');

        return $group;
    }
}
