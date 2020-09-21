<?php

namespace App\Models;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Group
 *
 * @author ivan
 */
class Group extends \Framework\Model\Model
{
    use \Framework\Model\TimeStamps;
    
    public $name;
    
    public $description;
    
    public $city;
    
    public $denomination;
    
    public $institute_id;
    
    public $group_leaders;
    
    public $group_leader_email;
    
    public $group_leader_phone;
    
    public $spiritual_movement;
    
    public $age_group;
    
    public $occasion_frequency;
    
    public $status;
    
    
    /**
     * @return AgeGroup
     */
    public function ageGroup()
    {
        return lang('age_group.' . $this->age_group);
    }
    
    public function occasionFrequency()
    {
        return lang('occasion_frequency.' . $this->occasion_frequency);
    }
    
    public function excerpt($words = 20)
    {
        return \Framework\Support\StringHelper::more($this->description, $words, '...');
    }
    
    public function url()
    {
        return route('kozosseg', ['kozosseg' => $this->slug()]);
    }
    
    public function slug()
    {
        return \Framework\Support\StringHelper::slugify($this->name . '-' . $this->id);
    }
    
}
