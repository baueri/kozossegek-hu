<?php

namespace App\Models\Traits;

use App\Models\UserLegacy;

trait HasUserColumn
{
    public function whereUser(UserLegacy $user, string $column = 'user_id'): self
    {
        return $this->where($column, $user->id);
    }
}
