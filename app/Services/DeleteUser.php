<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Groups;
use App\Repositories\Users;

class DeleteUser
{
    /**
     * @var Groups
     */
    private Groups $groups;

    /**
     * @var Users
     */
    private Users $users;

    public function __construct(Groups $groups, Users $users)
    {
        $this->groups = $groups;
        $this->users = $users;
    }

    public function softDelete(User $user): bool
    {
        $user->name .= "#{$user->email}";
        $user->email = null;

        $this->groups->deleteMultiple($this->groups->getGroupsByUser($user));

        return $this->users->delete($user);
    }

    public function hardDelete(User $user): bool
    {
        $this->groups->deleteMultiple($this->groups->getGroupsByUser($user), true);

        return $this->users->delete($user, true);
    }
}
