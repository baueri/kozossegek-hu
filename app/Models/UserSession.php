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
 * @property string $user_agent
 * @property string $ip_address
 */
class UserSession extends Entity
{
    use HasTimestamps;
}
