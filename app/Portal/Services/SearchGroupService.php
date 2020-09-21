<?php

namespace App\Portal\Services;

use Framework\Event\EventDisptatcher;

class SearchGroupService
{

    /**
     * @var App\Repositories\GroupRepository
     */
    private $groupRepo;

    /**
     * 
     * @param App\Repositories\GroupRepository $groupRepo
     */
    public function __construct(\App\Repositories\GroupRepository $groupRepo)
    {
        $this->groupRepo = $groupRepo;
    }
    
    /**
     * 
     * @param type $filter
     * @param type $page
     * @return type
     */
    public function search(\Framework\Support\Collection $filter, $page = 1)
    { 
        $keyword = $filter['search'];
        
        $groups = $this->groupRepo->search($keyword, $filter, $page);
        
        $this->logEvent($data);
        
        return $groups;
    }
    
    private function logEvent($data)
    {
        $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        EventDisptatcher::dispatch(new \App\Events\SearchTriggered('search', $data));
    }
}
