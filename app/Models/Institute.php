<?php

namespace App\Models;

use App\Models\Traits\InstituteTrait;
use Framework\Model\Entity;
use Framework\Model\HasTimestamps;
use Framework\Model\Relation\Has;
use Framework\Model\SoftDeletes;
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
 * @property string $lat
 * @property string $lon
 */
class Institute extends Entity
{
    use InstituteTrait;
    use HasTimestamps;
}
