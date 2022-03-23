<?php

namespace App\Models;

use App\Models\Traits\GroupTrait;
use Framework\Model\Entity;

/**
 * @property $name
 * @property $user_id
 * @property $on_days
 * @property $group_leaders
 * @property $age_group
 *
 * @property int $institute_id
 */
class ChurchGroup extends Entity
{
    use GroupTrait;
}
