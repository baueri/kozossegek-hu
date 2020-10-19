<?php

namespace App\Admin\Group\Services;

use App\Models\Group;
use App\Storage\Base64Image;
use Framework\Http\Message;
use Framework\Http\Request;

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
     * @param Request $request
     */
    public function update($id, Request $request)
    {
        /* @var $group Group */
        $group = $this->repository->findOrFail($id);
        
        $data = $request->except('id')->all();
        
        //dd($request->all());

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
