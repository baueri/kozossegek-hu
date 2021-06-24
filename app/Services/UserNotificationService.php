<?php

namespace App\Services;

use App\Models\User;

class UserNotificationService
{
    private User $user;

    public function __construct(User $user)    {
        $this->user = $user;
    }

    public function getLastNotification()
    {

    }
}
