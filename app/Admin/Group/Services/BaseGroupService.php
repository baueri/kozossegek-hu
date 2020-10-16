<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Admin\Group\Services;

use App\Models\Group;
use App\Repositories\Groups;
use App\Repositories\GroupViews;
use App\Repositories\Institutes;
use App\Services\RebuildSearchEngine;

/**
 * Description of BaseGroupService
 *
 * @author ivan
 */
abstract class BaseGroupService {

    /**
     * @var RebuildSearchEngine
     */
    private $searchEngineRebuilder;

    /**
     * @var GroupViews
     */
    private $groupViews;

    /**
     * @var Institutes
     */
    private $institutes;


    /**
     * @var Groups
     */
    protected $repository;

    /**
     * 
     * @param Groups $repository
     */
    public function __construct(Groups $repository, RebuildSearchEngine $searchEngineRebuilder) {
        $this->repository = $repository;
        $this->searchEngineRebuilder = $searchEngineRebuilder;
    }
    
    protected function updateSearchEngine(Group $group)
    {
        $this->searchEngineRebuilder->updateSearchEngine($group);
    }
}
