<?php

namespace App\Models;

use App\Models\Traits\InstituteTrait;
use Framework\Model\Entity;
use Framework\Model\HasTimestamps;
use Framework\Support\StringHelper;

/**
 * @property string $name
 * @property string $name2
 * @property string $city
 * @property string $address
 * @property null|string $leader_name
 * @property string $created_at
 * @property null|string $updated_at
 * @property null|string $deleted_at
 * @property null|string $district
 * @property null|int $user_id
 * @property int $approved
 * @property null|int $miserend_id
 * @property null|string $image_url
 * @property null|string $website
 */
class Institute extends Entity
{
    use InstituteTrait;
    use HasTimestamps;

    public function groupsUrl(): string
    {
        return route('portal.institute_groups', ['varos' => StringHelper::slugify($this->city), 'intezmeny' => StringHelper::slugify($this->name)]);
    }
}
