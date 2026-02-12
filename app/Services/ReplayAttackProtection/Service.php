<?php

declare(strict_types=1);

namespace App\Services\ReplayAttackProtection;

class Service
{
    public function generateNonce(string $name): string
    {
        $nonce = $_SESSION[$this->key($name)] = bin2hex(random_bytes(32));

        return base64_encode("{$nonce}:{$name}");
    }

    public function validate(?string $code): void
    {
        [$value, $name] = explode(':', base64_decode($code));

        $nonce = $this->getNonce($name);

        if (!$value || !$nonce || $nonce !== $value) {
            throw new Exception('Replay attack detected');
        }
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