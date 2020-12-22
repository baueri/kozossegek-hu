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
use Framework\File\File;
use Framework\File\FileManager;

/**
 * Description of BaseGroupService
 *
 * @author ivan
 */
abstract class BaseGroupService
{

    /**
     * @var RebuildSearchEngine
     */
    private RebuildSearchEngine $searchEngineRebuilder;

    /**
     * @var Groups
     */
    protected Groups $repository;

    /**
     * @var FileManager
     */
    private FileManager $fileManager;

    /**
     *
     * @param Groups $repository
     * @param RebuildSearchEngine $searchEngineRebuilder
     * @param FileManager $fileManager
     */
    public function __construct(Groups $repository, RebuildSearchEngine $searchEngineRebuilder, FileManager $fileManager)
    {
        $this->repository = $repository;
        $this->searchEngineRebuilder = $searchEngineRebuilder;
        $this->fileManager = $fileManager;
    }
    
    protected function updateSearchEngine(Group $group)
    {
        $this->searchEngineRebuilder->updateSearchEngine($group);
    }
    
    protected function syncTags(Group $group, array $tags = [])
    {
        $delete = builder('group_tags')->where('group_id', $id);

        if ($tags) {
            $delete->whereNotIn('tag', $tags ?: []);
        }

        $delete->delete();
        
        foreach($tags as $tag) {
            db()->execute('replace into group_tags (group_id, tag) values (?, ?)', $group->id, $tag);
        }
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
            //$image->saveThumbnail($group->getStorageImageDir() . 'thumbnails' . DS . $group->id . '_1.jpg');
        }
    }

    protected function uploadDocument(Group $group, ?File $document)
    {
        dd("folyt köv: BaseGroupService");
    }

    /**
     *
     * @param array $data
     * @return bool
     */
    protected function validate(array $data): bool
    {
        $requiredFields = ['name', 'denomination', 'institute_id', 'age_group', 'occasion_frequency', 'description', 'group_leaders', 'group_leader_email'];
        
        foreach ($requiredFields as $field) {
            if(!isset($data[$field]) || !$data[$field]) {
                return false;
            }
        }
        
        return true;
    }
}
