<?php

namespace App\Models;

use App\Models\Traits\GroupTrait;
use Framework\Model\Entity;
use Framework\Support\StringHelper;

/**
 * @property-read null|string $name
 * @property-read null|string $image_url
 * @property-read null|string $institute_image
 * @property-read null|string $institute_name
 * @property-read null|string $city
 * @property-read null|string $institute_id
 * @property-read null|string $on_days
 * @property-read null|string $group_leaders
 * @property-read null|string $user_id
 * @property-read null|string $age_group
 * @property-read null|string $leader_name
 * @property-read null|string $group_leader_email
 * @property-read null|string $district
 * @property-read null|string $institute_name2
 * @property-read null|string $spiritual_movement
 * @property-read null|string $pending
 */
class ChurchGroupView extends Entity
{
    use GroupTrait;

    private ?string $cachedUrl = null;

    public function getThumbnail(): string
    {
        if ($this->image_url) {
            return $this->image_url;
        }

        return $this->institute_image ?: '/images/default_thumbnail.jpg';
    }

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
}
