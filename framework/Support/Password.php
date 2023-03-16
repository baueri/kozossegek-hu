<?php

declare(strict_types=1);

namespace Framework\Support;

class Password
{
    public static function hash($password, ?string $algo = PASSWORD_BCRYPT): string
    {
        return password_hash($password, $algo);
    }

    public static function verify($password, $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }
}
