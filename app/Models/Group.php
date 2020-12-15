<?php

namespace App\Models;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use Framework\Model\Model;
use Framework\Model\TimeStamps;
use Framework\Support\StringHelper;
use App\Helpers\GroupHelper;
use App\Helpers\InstituteHelper;

/**
 * Description of Group
 *
 * @author ivan
 */
class Group extends Model
{
    use TimeStamps;

    public $name;

    public $description;

    public $denomination;

    public $institute_id;

    public $group_leaders;

    public $group_leader_email;

    public $group_leader_phone;

    public $spiritual_movement_id;

    public $age_group;

    public $occasion_frequency;

    public $status;

    public $on_days;
    
    public $user_id;
    
    public $pending;

    /**
     * @var Institute|null
     */
    public $institute;


    /**
     * @return string
     */
    public function ageGroup(): string
    {
        return GroupHelper::parseAgeGroup($this->age_group);
    }

    public function getAgeGroups()
    {
        return GroupHelper::getAgeGroups($this->age_group);
    }

    public function denomination(): string
    {
        return lang("denomination.{$this->denomination}");
    }

    public function getDays()
    {
        $days = array_filter(explode(',', $this->on_days));
        $daysTranslated = [];

        foreach ($days as $day) {
            $daysTranslated[$day] = lang("day.$day");
        }

        return $daysTranslated;
    }

    /**
     * @return string
     */
    public function occasionFrequency(): string
    {
        return lang('occasion_frequency.' . $this->occasion_frequency);
    }

    /**
     * @param int $words
     * @return string
     */
    public function excerpt($words = 25): string
    {
        return StringHelper::more(strip_tags($this->description), $words, '...');
    }

    /**
     * @return string
     */
    public function slug(): string
    {
        return StringHelper::slugify($this->name . '-' . $this->id);
    }

    public function getImages()
    {
        $dir = $this->getStorageImageDir();

        $images = collect(glob("$dir*.jpg"))->map(function($image) {
            return "/media/groups/images/" . basename($image);
        });

        if ($images->isNotEmpty()) {
            return $images->all();
        }

        if (file_exists(InstituteHelper::getImageStoragePath($this->institute_id))) {
            return [InstituteHelper::getImageRelPath($this->institute_id)];
        }

        return ["/images/default_thumbnail.jpg"];

    }

    /**
     * @todo !!!
     * @return string
     */
    public function getThumbnail()
    {
        return $this->getFirstImage();
    }

    public function getFirstImage()
    {
        return $this->getImages()[0];
    }

    public function getStorageImageDir()
    {
        return GroupHelper::getStoragePath($this->id);
    }


    /**
     * @todo képmentést megoldani!!!
     * @return boolean [description]
     */
    public function hasImage()
    {
        return false;
    }
    
    public function isVisibleBy(?User $user)
    {
        if ($user && ($user->isAdmin() || $this->user_id == $user->id)) {
            return true;
        }
        
        if ($this->pending == 0 && $this->status == \App\Enums\GroupStatusEnum::ACTIVE) {
            return true;
        }
        
        return false;
    }
    
    public function getEditUrl()
    {
        return route('portal.edit_my_group', $this);
    }
}
