<?php

namespace Framework\Support;

use Framework\PasswordGenerator;

class Password
{
    private static string $algo = PASSWORD_BCRYPT;

    public readonly string $password;

    public readonly string $hash;

    public function __construct(string $password)
    {
        $this->password = $password;
        $this->hash = static::hash($password);
    }

    public static function hash($password): string
    {
        return password_hash($password, static::$algo);
    }

    public static function verify($password, $hashedPassword): bool
    {
        return password_verify($password, $hashedPassword);
    }

    public static function generate(?int $length = null, array $settings = []): static
    {
        return new static((new PasswordGenerator($length, $settings))->generate());
    }
}
