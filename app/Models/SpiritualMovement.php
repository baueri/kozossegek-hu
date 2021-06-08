<?php

namespace App\Models;

use Framework\Model\Model;
use Framework\Support\StringHelper;

class SpiritualMovement extends Model
{
    public ?string $name = null;

    public ?string $slug = null;

    public ?string $description = null;

    public ?string $image_url = null;

    public ?string $website = null;

    public ?bool $highlighted = null;

    public function excerpt()
    {
        return StringHelper::more(strip_tags($this->description), 40, '...');
    }
}
