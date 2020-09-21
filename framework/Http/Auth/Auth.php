<?php


namespace Framework\Http\Auth;


use App\Models\User;
use Framework\Database\Database;
use Framework\Http\Auth\Authenticators\Authenticator;
use Framework\Http\Auth\Authenticators\UserAuthenticator;
use Framework\Http\Request;
use Framework\Http\Session;

class Auth
{
    /**
     * @var User
     */
    private static $user;

    public static function user()
    {
        return static::$user;
    }

    public static function setUser(?User $user)
    {
        static::$user = $user;
    }

    public static function logIn(?User $user)
    {
        if ($user) {
            static::setUser($user);
            db()->insert('replace into user_sessions (session_id, user_id, created_at) values(?, ?, CURRENT_TIMESTAMP)',
                [ session_id(), $user->id ]);
        }
    }

    public static function logout()
    {
        db()->execute('delete from user_sessions where session_id=?', [session_id()]);
        static::$user = null;
    }

    public static function  authenticate()
    {
        $user = static::authenticator()->authenticate(app()->get(Request::class));

        if ($user) {
            static::setUser($user);
        }
    }

    /**
     * @return UserAuthenticator
     */
    private static function authenticator()
    {
        return app()->make(UserAuthenticator::class);
    }
}