<?php

namespace App\Services;

use App\Models\User;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\Users;

class DeleteUser
{
    public function __construct(
        private readonly ChurchGroups $groups,
        private readonly Users $users
    ) {
    }

    public function softDelete(User $user): bool
    {
        $user->name .= "#{$user->email}";
        $user->email = null;

        $this->groups->of($user)->softDelete();

        return $this->users->deleteModel($user);
    }
}
