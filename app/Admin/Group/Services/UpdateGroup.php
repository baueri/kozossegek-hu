<?php

namespace App\Admin\Group\Services;

use App\Models\Group;
use Framework\Http\Message;
use Framework\Http\Request;
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
     * @param Request|Collection $request
     */
    public function update($id, $request)
    {
        if (is_array($request)) {
            $request = collect($request);
        }
        
        /* @var $group Group */
        $group = $this->repository->findOrFail($id);
        
        $data = $request->except('id')->all();
        
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
        
        $this->syncImages($group, [$request['image']]);
        
        Message::success('Sikeres mentés.');
    }
    
}
