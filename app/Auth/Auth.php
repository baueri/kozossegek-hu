<?php

namespace App\Auth;

use App\Models\UserLegacy;
use App\Services\User\LegalNoticeService;

final class Auth
{
    private static ?UserLegacy $user = null;

    public static function setUser(UserLegacy $user): void
    {
        self::$user = $user;
    }

    public static function login(UserLegacy $user): void
    {
        app()->make(LegalNoticeService::class)->setLegalNoticeSessionFor($user);

        db()->execute(
            'replace into user_sessions (unique_id, user_id, created_at)
                    values(?, ?, CURRENT_TIMESTAMP)',
            session_id(),
            $user->id
        );
        db()->execute('update users set last_login=CURRENT_TIMESTAMP where id=?', $user->id);

        self::setUser($user);
    }

    public static function logout(): void
    {
        db()->execute('delete from user_sessions where unique_id=?', session_id());

        session_destroy();

        self::$user = null;

        session_start();

        session_id(session_create_id());
    }

    public static function loggedIn(): bool
    {
        return (bool) self::$user;
    }

    public static function user(): ?UserLegacy
    {
        return self::$user;
    }
}
