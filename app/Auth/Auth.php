<?php


namespace App\Auth;


use App\Models\User;

class Auth
{
    /**
     * @var User|null
     */
    protected static ?User $user = null;

    /**
     * @param User $user
     */
    public static function setUser(User $user): void
    {
        self::$user = $user;
    }

    /**
     *
     * @param User $user
     */
    public static function login(User $user)
    {
        db()->execute('replace into user_sessions (unique_id, user_id, created_at) values(?, ?, CURRENT_TIMESTAMP)', session_id(), $user->id);
        db()->execute('update users set last_login=CURRENT_TIMESTAMP where id=?', $user->id);

        static::setUser($user);
    }

    public static function logout()
    {
        db()->execute('delete from user_sessions where unique_id=?', session_id());

        session_destroy();

        static::$user = null;

        session_start();

        session_id(session_create_id());
    }

    /**
     * @return bool
     */
    public static function loggedIn()
    {
        return (bool) static::$user;
    }

    /**
     *
     * @return User
     */
    public static function user()
    {
        return static::$user;
    }
}
