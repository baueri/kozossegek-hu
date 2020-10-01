<?php


namespace Framework\Http;


class Session
{
    public static function get($key, $default = null)
    {
        if (static::has($key)) {
            return $_SESSION[$key];
        }

        return $default;
    }

    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function flash($key, $default = null)
    {
        $value = static::get($key, $default);

        static::forget($key);

        return $value;
    }

    public static function forget($key)
    {
        unset($_SESSION[$key]);
    }

    public static function id()
    {
        return session_id();
    }
}
