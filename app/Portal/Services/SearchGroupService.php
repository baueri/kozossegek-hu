<?php

namespace App\Portal\Services;

use Framework\Event\EventDisptatcher;
use Framework\Support\Collection;

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
     * @param Collection $filter
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public function search(Collection $filter, $page = 1, $perPage = 21)
    { 
        $keyword = $filter['search'];
        
        $groups = $this->groupRepo->search($keyword, $filter, $page, $perPage);
        
        $this->logEvent($filter);
        
        return $groups;
    }
    
    private function logEvent(Collection $filter)
    {
        EventDisptatcher::dispatch(new \App\Events\SearchTriggered('search', $filter->set('user_agent', $_SERVER['HTTP_USER_AGENT'])->toArray()));
    }
}
