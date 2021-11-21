<?php

namespace App\Models;

use Framework\Support\StringHelper;
use Legacy\Group;

/**
 * @deprecated
 */
class GroupView extends Group
{
    /**
     * Intézmény neve
     * @var string|null
     */
    public $institute_name;

    /**
     * Intézmény alternatív neve
     * @var string
     */
    public $institute_name2;

    /**
     * Intézményvezető neve
     *
     * @var string|null
     */
    public ?string $leader_name;

    /**
     * Lelkiségi mozgalom
     *
     * @var string|null
     */
    public ?string $spiritual_movement;

    /**
     * Város
     * @var string|null
     */
    public ?string $city;

    /**
     * Városrész
     * @var string|null
     */
    public ?string $district;

    /**
     * @var string
     */
    public $institute_image;

    private ?string $cachedUrl = null;

    /**
     * Kapcsolattartó email címe
     * @var string
     */
    public $group_leader_email;

    /**
     * Karbantartó neve
     * @var string
     */
    public $user_name;

    public function url(): string
    {
        if ($this->cachedUrl) {
            return $this->cachedUrl;
        }

        $intezmeny = StringHelper::slugify($this->institute_name);
        $varos = StringHelper::slugify($this->city);

        $data = ['varos' => $varos, 'intezmeny' => $intezmeny, 'kozosseg' => $this->slug()];
        return $this->cachedUrl = get_site_url() . route('kozosseg', $data);
    }

    public function getThumbnail(): string
    {
        if ($this->image_url) {
            return $this->image_url;
        }

        return $this->institute_image ?: '/images/default_thumbnail.jpg';
    }
}
