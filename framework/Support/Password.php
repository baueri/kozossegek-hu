<?php

namespace Framework\Support;

class Password
{
    private static string $algo = PASSWORD_BCRYPT;

    public static function hash($password): string
    {
        return password_hash($password, static::$algo);
    }

    public static function verify($password, $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }
}
