<?php

declare(strict_types=1);

namespace App\Models;

use Framework\Model\Entity;
use Framework\Model\HasTimestamps;

/**
 * @property string $app_name
 * @property string $app_secret
 * @property string $user_id
 */
class ThirdPartyCredential extends Entity
{
    use HasTimestamps;
}
