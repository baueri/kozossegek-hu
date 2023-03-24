<?php

namespace App\Models;

use App\Models\Traits\GroupTrait;
use Framework\Model\Entity;

/**
 * @property int $institute_id
 * @property $document
 * @property User|null $manager
 */
class ChurchGroup extends Entity
{
    use GroupTrait;
}
