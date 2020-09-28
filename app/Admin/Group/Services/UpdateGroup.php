<?php

namespace App\Admin\Group\Services;

/**
 * Description of UpdateGroup
 *
 * @author ivan
 */
class UpdateGroup
{

    /**
     * @var \App\Repositories\GroupRepository
     */
    private $repository;

    /**
     * 
     * @param \App\Repositories\GroupRepository $repository
     */
    public function __construct(\App\Repositories\GroupRepository $repository) {
        
        $this->repository = $repository;
    }
    
    /**
     * 
     * @param int $id
     * @param array $data
     */
    public function update($id, array $data)
    {
        $group = $this->repository->findOrFail($id);
        
        $group->update($data);
        
        $this->repository->update($group);
        
        \Framework\Http\Message::success('Sikeres mentés.');
    }
}
