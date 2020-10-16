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

use App\Models\AgeGroup;

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
        return StringHelper::more($this->description, $words, '...');
    }

    /**
     * @return string
     */
    public function url(): string
    {
        return route('kozosseg', ['kozosseg' => $this->slug()]);
    }

    /**
     * @return string
     */
    public function slug(): string
    {
        return StringHelper::slugify($this->name . '-' . $this->id);
    }

    public function getImages($thumbnail = false)
    {
        if ($this->hasImage()) {
            return [];
        }

        if (file_exists(InstituteHelper::getInstituteAbsPath($this->institute_id, $thumbnail))) {
            return [InstituteHelper::getInstituteRelPath($this->institute_id, $thumbnail)];
        }

        $suffix = $thumbnail ? '_wide' : '';

        return ["/images/default_thumbnail$suffix.jpg"];

    }

    /**
     * @todo !!!
     * @return string
     */
    public function getThumbnail()
    {
        return $this->getFirstImage(true);
    }

    public function getFirstImage($thumbnail = false)
    {
        return $this->getImages($thumbnail)[0];
    }

    /**
     * @todo képmentést megoldani!!!
     * @return boolean [description]
     */
    public function hasImage()
    {
        return false;
    }
}
