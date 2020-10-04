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

    /**
     * @var Institute|null
     */
    public $institute;


    /**
     * @return string
     */
    public function ageGroup(): string
    {
        return lang('age_group.' . $this->age_group);
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

}
