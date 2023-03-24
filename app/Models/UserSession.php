<?php

declare(strict_types=1);

namespace App\Models;

use Framework\Model\Entity;
use Framework\Model\HasTimestamps;

/**
 * @property $unique_id
 * @property $user_id
 * @property $created_at
 * @property User|null $user
 */
class UserSession extends Entity
{
    use HasTimestamps;
}
