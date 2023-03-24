<?php

namespace App\Models;

use App\Models\Traits\GroupTrait;
use App\QueryBuilders\ChurchGroups;
use Framework\Model\Entity;

/**
 * @property string $name
 * @property string|int $user_id
 * @property string $on_days
 * @property string $group_leaders
 * @property string $age_group
 * @property string $confirmed_at
 * @property string $notified_at
 * @property int $institute_id
 * @property $document
 * @property User|null $manager
 * @property User $maintainer
 * @property \Framework\Support\Collection $tags
 */
class ChurchGroup extends Entity
{
    use GroupTrait;

    protected ?string $builder = ChurchGroups::class;
}
