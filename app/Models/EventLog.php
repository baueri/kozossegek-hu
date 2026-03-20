<?php

declare(strict_types=1);

namespace App\Models;

use Framework\Model\Entity;
use Framework\Support\Collection;

/**
 * @property int $id
 * @property string $type
 * @property string $log
 * @property int $user_id
 * @property string $created_at
 */
class EventLog extends Entity
{
    protected array $casts = [
        'log' => [Collection::class, 'fromJson']
    ];
}
