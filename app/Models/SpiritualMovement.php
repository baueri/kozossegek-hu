<?php

namespace App\Models;

use Framework\Model\Entity;
use Framework\Support\StringHelper;

/**
 * @property null|string $name
 * @property null|string $slug
 * @property null|string $description
 * @property null|string $image_url
 * @property null|string $website
 * @property null|string $highlighted
 */
class SpiritualMovement extends Entity
{
    public function excerpt(): string
    {
        return StringHelper::more(strip_tags($this->description), 40, '...');
    }
}
