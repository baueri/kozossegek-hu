<?php

namespace App\Services\SystemAdministration;

use App\Repositories\Users;

/**
 * Több, mint egy éve törölt fehasználók törlése
 *
 * @package App\Services\SystemAdministration
 */
class ForceDeleteDeletedUsers
{
    private Users $users;

    public function __construct(Users $users)
    {
        $this->users = $users;
    }

    public function run()
    {
        $users = $this->getUsersDeletedBefore(date('Y-m-d', strtotime('-1 year')));

        $this->users->deleteMultiple($users);
    }

    private function getUsersDeletedBefore($date)
    {
        $rows = $this->users->getBuilder()
            ->apply('deletedEarlierThan', $date)
            ->get();

        return $this->users->getInstances($rows);
    }
}
