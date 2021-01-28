<?php

namespace App\Models;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use App\Enums\GroupStatusEnum;
use App\Enums\JoinMode;
use Framework\File\File;
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

    /**
     * Közösség neve
     * @var string
     */
    public $name;

    /**
     * Bemutatkozás
     * @var string
     */
    public $description;

    /**
     * Felekezet
     * @var string
     */
    public $denomination;

    /**
     * Intézmény azonosító
     * @var int
     */
    public $institute_id;

    /**
     * Közösségvezetők
     * @var string
     */
    public $group_leaders;

    /**
     * Kapcsolattartó email címe
     * @var string
     */
    public $group_leader_email;

    /**
     * Kapcsolattartó telefonszáma
     * @var string
     */
    public $group_leader_phone;

    /**
     * Lelkiségi mozgalom azonosítója
     * @var int
     */
    public $spiritual_movement_id;

    /**
     * Korosztályok
     * @var string
     */
    public $age_group;

    /**
     * Alkalmak gyakorisága
     * @var string
     */
    public $occasion_frequency;

    /**
     * Megjelenési állapot
     * @see GroupStatusEnum
     * @var string
     */
    public $status;

    /**
     * Mely napokon tartják az alkalmakat
     * @var string
     */
    public $on_days;

    /**
     * Karbantartó felhasználó azonosítója
     * @var int
     */
    public $user_id;

    /**
     * Függőben van-e (0,1)
     * @var int
     */
    public $pending;

    /**
     * Feltöltött dokumentum neve
     * @var string
     */
    public $document;

    /**
     * @var Institute|null
     */
    public $institute;

    /**
     * Csatlakozás módja
     *
     * @var string
     */
    public $join_mode;

    /**
     * @var string
     */
    public $tags;


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

        return collect($daysTranslated);
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

        $images = collect(glob("$dir*.jpg"))->map(function ($image) {
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


    public function joinMode(): string
    {
        return JoinMode::getText($this->join_mode) ?? '';
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

        if ($this->pending == 0 && $this->status == GroupStatusEnum::ACTIVE) {
            return true;
        }

        return false;
    }

    public function getEditUrl()
    {
        return route('portal.edit_group', $this);
    }

    public function isEditableBy(?User $user)
    {
        if (!$user) {
            return false;
        }

        return $user->isAdmin() || $user->id == $this->user_id;
    }

    public function hasDocument()
    {
        return file_exists($this->getDocumentPath());
    }

    public function getDocument(): ?File
    {
        return new File($this->getDocumentPath());
    }

    public function getDocumentPath()
    {
        if (!$this->document) {
            return '';
        }

        return GroupHelper::getStoragePath($this->id) . $this->document;
    }

    public function getDocumentUrl()
    {
        return "/my-group/{$this->id}/download-document";
    }
}
