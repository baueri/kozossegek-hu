<?php

declare(strict_types=1);

namespace App\Services\ReplayAttackProtection;

class Service
{
    public function generateNonce(string $name): string
    {
        $nonce = $_SESSION[$this->key($name)] = md5((string) time());

        return base64_encode("{$nonce}:{$name}");
    }

    public function validate(string $code): bool
    {
        [$value, $name] = explode(':', base64_decode($code));

        $nonce = $this->getNonce($name);

        if (!$value || !$nonce) {
            return false;
        }

        return $nonce === $value;
    }

    public function forget(string $code): void
    {
        [, $name] = explode(':', base64_decode($code));

        unset($_SESSION[$this->key($name)]);
    }

    protected function getNonce(string $name): ?string
    {
        return $_SESSION[$this->key($name)] ?? null;
    }

    protected function key(string $name): string
    {
        return "rap:{$name}";
    }
}