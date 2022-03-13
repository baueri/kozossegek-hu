<?php

namespace App\Services;

use App\Models\User;
use App\QueryBuilders\Users;
use App\Repositories\Groups;

class DeleteUser
{
    public function __construct(
        private Groups $groups,
        private Users $users
    ) {
    }

    public function softDelete(User $user): bool
    {
        $user->name .= "#{$user->email}";
        $user->email = null;

        $this->groups->deleteMultiple($this->groups->getGroupsByUser($user));

        return $this->users->deleteModel($user);
    }
}
