<?php

namespace App\Helpers;
use App\Exception\HoneypotException;

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
     * @throws HoneypotException
     */
    public static function validate(string $id, string $hashVal): void
    {
        $checkTime = $_SESSION['honey_pot'][$id]['honeypot_check_time'] ?? null;
        $check_hash = $_SESSION['honey_pot'][$id]['honeypot_check_hash'] ?? null;
        if (!$checkTime || !$check_hash || time() - $checkTime < 5 || $hashVal) {
            throw new HoneypotException('spam check failed');
        }

        unset($_SESSION['honey_pot'][$id]);
    }
}
