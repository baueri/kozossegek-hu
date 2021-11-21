<?php

namespace App\Repositories;

use App\Models\User;
use Framework\Repository;
use Legacy\Group;

/**
 * @phpstan-extends \Framework\Repository<\Legacy\Group>
 */
class Groups extends Repository
{
    public function getGroupsByUser(User $user)
    {
        return $this->getInstances(
            $this->getBuilder()
                ->where('user_id', $user->id)
                ->get()
        );
    }

    public static function getModelClass(): string
    {
        return Group::class;
    }

    public static function getTable(): string
    {
        return 'church_groups';
    }
}
