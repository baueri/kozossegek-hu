<?php

namespace App\Admin\Group\Services;

/**
 * Description of DeleteGroup
 *
 * @author ivan
 */
class DeleteGroup {

    /**
     * @var \App\Repositories\Groups
     */
    private $repository;

    /**
     * 
     * @param \App\Repositories\Groups $repository
     */
    public function __construct(\App\Repositories\Groups $repository) {
        $this->repository = $repository;
    }
    
    public function delete($groupId)    
    {
        $group = $this->repository->findOrFail($groupId);
        
        $this->repository->delete($group);
        
        \Framework\Http\Message::warning('Közösség lomtárba helyezve.');
    }
}
