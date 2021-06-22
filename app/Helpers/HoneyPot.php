<?php

namespace App\Helpers;

use Framework\Exception\UnauthorizedException;

class HoneyPot
{
    public static function getHash(string $id)
    {
        $_SESSION['honey_pot'][$id] = [
            'honeypot_check_time' => $checkTime = time(),
            'honeypot_check_hash' => md5($checkTime)
        ];

        return $_SESSION['honey_pot'][$id]['honeypot_check_hash'];
    }

    /**
     * @param string $id
     * @param string $hashVal
     * @throws UnauthorizedException
     */
    public static function validate(string $id, string $hashVal): void
    {
        $checkTime = $_SESSION['honey_pot'][$id]['honeypot_check_time'] ?? null;
        $check_hash = $_SESSION['honey_pot'][$id]['honeypot_check_hash'] ?? null;

        if (!$checkTime || !$check_hash || time() - $checkTime < 5 || $hashVal !== $check_hash) {
            throw new UnauthorizedException('spam check failed');
        }

        unset($_SESSION['honey_pot'][$id]);
    }
}
