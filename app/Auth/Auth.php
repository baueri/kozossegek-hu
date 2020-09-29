<?php


namespace App\Auth;


use App\Models\User;

class Auth
{
    /**
     * @var User
     */
    protected static $user;

    /**
     * @param User $user
     */
    public static function setUser(User $user): void
    {
        self::$user = $user;
    }

    public static function login(User $user)
    {
        db()->execute('replace into user_sessions (session_id, user_id, created_at) values(?, ?, CURRENT_TIMESTAMP)', session_id(), $user->id);

        static::setUser($user);
    }

    public static function logout()
    {
        db()->execute('delete from user_sessions where session_id=?', session_id());

        static::$user = null;
    }

    /**
     * @return bool
     */
    public static function loggedIn()
    {
        return (bool) static::$user;
    }
}