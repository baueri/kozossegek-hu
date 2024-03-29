<?php

namespace App\QueryBuilders;

use App\Models\SpiritualMovement;
use App\Models\User;
use App\QueryBuilders\Relations\HasManyChurchGroupViews;
use Framework\Model\EntityQueryBuilder;

/**
 * @phpstan-extends EntityQueryBuilder<SpiritualMovement>
 */
class SpiritualMovements extends EntityQueryBuilder
{
    use HasManyChurchGroupViews;

    public function hightLighted(): self
    {
        return $this->where('highlighted', 1);
    }

    public function forUser(User $user): self
    {
        return $this->whereRaw('id in (SELECT spiritual_movement_id FROM spiritual_movement_administrators WHERE user_id = ?)', [$user->id]);
    }
}
