<?php

namespace App\QueryBuilders;

use App\Models\ChurchGroupView;
use App\Models\SpiritualMovement;
use App\Models\UserLegacy;
use App\QueryBuilders\Relations\HasManyChurchGroupViews;
use Framework\Model\EntityQueryBuilder;

/**
 * @phpstan-extends \Framework\Model\EntityQueryBuilder<\App\Models\SpiritualMovement>
 */
class SpiritualMovements extends EntityQueryBuilder
{
    use HasManyChurchGroupViews;

    protected static function getModelClass(): string
    {
        return SpiritualMovement::class;
    }

    public function forUser(UserLegacy $user): self
    {
        return $this->whereRaw('id in (SELECT spiritual_movement_id FROM spiritual_movement_administrators WHERE user_id = ?)', [$user->id]);
    }
}
