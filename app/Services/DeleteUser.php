<?php

namespace App\Services;

use App\Models\UserLegacy;
use App\Repositories\Groups;
use App\Repositories\UsersLegacy;

class DeleteUser
{
    public function __construct(
        private Groups $groups,
        private UsersLegacy $users
    ) {
    }

    public function softDelete(UserLegacy $user): bool
    {
        $user->name .= "#{$user->email}";
        $user->email = null;

        $this->groups->deleteMultiple($this->groups->getGroupsByUser($user));

        return $this->users->delete($user);
    }

    public function hardDelete(UserLegacy $user): bool
    {
        $this->groups->deleteMultiple($this->groups->getGroupsByUser($user), true);

        return $this->users->delete($user, true);
    }
}
