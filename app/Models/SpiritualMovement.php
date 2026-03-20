<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SpiritualMovementType;
use App\Portal\BreadCrumb\BreadCrumb;
use App\Portal\BreadCrumb\BreadCrumbable;
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
 * @property null|string $type
 */
class SpiritualMovement extends Entity implements BreadCrumbable
{
    use EntitySiteMappable;

    public function excerpt(): string
    {
        return StringHelper::more(strip_tags($this->description), 40, '...');
    }

    public function getUrl(): string
    {
        return route("portal.{$this->type}.view", ['slug' => $this->slug]);
    }

    public function priority(): ?string
    {
        return '0.8';
    }

    public function changeFreq(): ChangeFreq
    {
        return ChangeFreq::monthly;
    }

    public function type(): string
    {
        return SpiritualMovementType::from($this->type)->translate();
    }

    public function getBreadCrumb(): BreadCrumb
    {
        return new BreadCrumb([
            [
                'name' => 'Lelkiségi mozgalmak',
                'position' => 1,
                'url' => route('portal.spiritual_movements')
            ],
            [
                'name' => $this->name,
                'position' => 2,
            ]
        ]);
    }
}
