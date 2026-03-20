<?php

namespace Framework\Http;

class Session
{
    public static function start(): void
    {
        session_start();
        self::token();
    }

    public static function get($key, $default = null)
    {
        if (static::has($key)) {
            return $_SESSION[$key];
        }

        return $default;
    }

    public static function has($key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function set($key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function push($key, $value): void
    {
        $_SESSION[$key] ??= [];

        $_SESSION[$key][] = $value;
    }

    public static function flash($key, $default = null)
    {
        $value = static::get($key, $default);

        static::forget($key);

        return $value;
    }

    public static function forget($key): void
    {
        unset($_SESSION[$key]);
    }

    public static function id(): bool|string
    {
        return session_id();
    }

    public static function token(): string
    {
        return $_SESSION['_token'] ??= bin2hex(random_bytes(32));
    }

    public static function all(): array
    {
        return $_SESSION;
    }
}
