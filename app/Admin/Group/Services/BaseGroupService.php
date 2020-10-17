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
use App\Storage\Base64Image;

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
    
    protected function syncImages(Group $group, $images)
    {
        $images = array_filter($images);
        
        if (!$images) {
            return null;
        }
        
        foreach($images as $imageSource) {
            $image = new Base64Image($imageSource);
            $image->saveImage($group->getStorageImageDir() . $group->id . '_1.jpg');
            $image->saveThumbnail($group->getStorageImageDir() . 'thumbnails' . DS . $group->id . '_1.jpg');
        }
    }
}
