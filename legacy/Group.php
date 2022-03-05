<?php

namespace Legacy;

use App\Enums\GroupStatusEnum;
use App\Helpers\InstituteHelper;
use App\Models\Traits\GroupTrait;
use Framework\Model\Model;

class Group extends Model
{
    use TimeStamps;
    use GroupTrait;

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
     * @var string
     */
    public $image_url;

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

    public function setToPending(): self
    {
        $this->pending = 1;
        return $this;
    }
}
