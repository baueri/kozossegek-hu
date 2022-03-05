<?php

namespace App\Models;

use App\Services\SystemAdministration\SiteMap\ChangeFreq;
use App\Services\SystemAdministration\SiteMap\EntitySiteMappable;
use Framework\Model\Entity;
use Framework\Support\StringHelper;

/**
 * @property null|string $name
 * @property null|string $slug
 * @property null|string $description
 * @property null|string $image_url
 * @property null|string $website
 * @property null|string $highlighted
 * @property string $created_at
 * @property null|string $updated_at
 */
class SpiritualMovement extends Entity
{
    use EntitySiteMappable;

    public function excerpt(): string
    {
        return StringHelper::more(strip_tags($this->description), 40, '...');
    }

    public function getUrl(): string
    {
        return route('portal.spiritual_movement.view', ['slug' => $this->slug]);
    }

    public function priority(): ?string
    {
        return $this->highlighted ? '0.8' : '0.7';
    }

    public function changeFreq(): ChangeFreq
    {
        return ChangeFreq::monthly;
    }
}
