<?php

namespace App\Models\Traits;

use App\Models\User;

trait HasUserColumn
{
    public function whereUser(User $user, string $column = 'user_id')
    {
        return $this->where($column, $user->id);
    }
}