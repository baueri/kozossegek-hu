<?php

declare(strict_types=1);

namespace Framework\Support;

use Framework\PasswordGenerator;

class Password
{
    public readonly string $password;

    public readonly string $hash;

    public function __construct(string $password)
    {
        $this->password = $password;
        $this->hash = static::hash($password);
    }

    public static function hash($password, ?string $algo = PASSWORD_BCRYPT): string
    {
        return password_hash($password, $algo);
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
