<?php

namespace App\Repositories;

use App\Models\SpiritualMovement;
use Framework\Repository;

/**
 * Class SpiritualMovements
 * @package App\Repositories
 * @extends Repository<\App\Models\SpiritualMovement>
 */
class SpiritualMovements extends Repository
{
    /**
     * @inheritDoc
     */
    public static function getModelClass(): string
    {
        return SpiritualMovement::class;
    }

    /**
     * @return string
     */
    public static function getTable(): string
    {
        return 'spiritual_movements';
    }
}
