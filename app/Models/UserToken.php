<?php

declare(strict_types=1);

namespace App\Models;

use Framework\Model\Entity;

/**
 * @property $token
 * @property $email
 * @property $page
 * @property $expires_at
 */
class UserToken extends Entity
{
    public function getUrl(): string
    {
        return "{$this->page}?token={$this->token}";
    }
}