<?php

namespace App\Admin\Group\Services;

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
     * @param array $data
     */
    public function update($id, array $data)
    {
        $group = $this->repository->findOrFail($id);

        $data['age_group'] = implode(',', $data['age_group']);

        $data['on_days'] = implode(',', $data['on_days']);

        $delete = builder('group_tags')->where('group_id', $id);

        if ($data['tags']) {
            $delete->whereNotIn('tag', $data['tags'] ?: []);
        }

        $delete->delete();

        foreach($data['tags'] as $tag) {
            db()->execute('replace into group_tags (group_id, tag) values (?, ?)', $id, $tag);
        }

        $group->update($data);

        $this->repository->update($group);
        
        $this->updateSearchEngine($group);

        \Framework\Http\Message::success('Sikeres mentés.');
    }
    
}
