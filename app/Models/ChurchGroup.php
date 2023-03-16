<?php

namespace App\Models;

use App\Models\Traits\GroupTrait;
use Framework\Model\Entity;

/**
 * @property int $institute_id
 * @property $document
 */
class ChurchGroup extends Entity implements ChurchGroupInterface
{
    use GroupTrait;
}
