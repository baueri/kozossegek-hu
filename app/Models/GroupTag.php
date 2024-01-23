<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Tag;
use Framework\Model\Entity;

/**
 * @property null|string $tag
 * @property null|string $group_id
 */
class GroupTag extends Entity
{
    public function translate()
    {
        return Tag::from($this->tag)->translate();
    }
}