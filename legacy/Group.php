<?php

namespace Legacy;

use App\Enums\GroupStatus;
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
     * @see GroupStatus
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
}
