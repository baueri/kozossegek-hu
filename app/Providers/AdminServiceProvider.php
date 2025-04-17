<?php

namespace App\Providers;

use App\Auth\Auth;
use App\Enums\UserRight;
use App\QueryBuilders\ChurchGroupViews;
use App\QueryBuilders\SpiritualMovements;
use Framework\Middleware\Before;

class AdminServiceProvider implements Before
{
    public function before(): void
    {
        $user = Auth::user();

        if ($user->hasRight(UserRight::MANAGE_SPIRITUAL_MOVEMENT)) {
            app()->bind(SpiritualMovements::class, function () use ($user) {
                return (new SpiritualMovements())->forUser($user);
            });
        }

        if ($user->hasRight(UserRight::MANAGE_SPIRITUAL_MOVEMENT_GROUPS)) {
            app()->bind(ChurchGroupViews::class, function () use ($user) {
                return (new ChurchGroupViews())->forUser($user);
            });
        }
    }
}
