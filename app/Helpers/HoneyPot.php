<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Exception\HoneypotException;

class HoneyPot
{
    public const MIN_SEC = 8;

    public static function getHash(string $id)
    {
        $_SESSION['honey_pot'][$id] = [
            'honeypot_check_time' => $checkTime = time(),
            'honeypot_check_hash' => md5((string) $checkTime)
        ];

        return $_SESSION['honey_pot'][$id]['honeypot_check_hash'];
    }

    /**
     * @throws HoneypotException
     */
    public static function validate(string $id, string $hashVal): void
    {
        if ($hashVal) {
            throw new HoneypotException('spam check failed', reason: 'filled_honeypot');
        }

        $checkTime = $_SESSION['honey_pot'][$id]['honeypot_check_time'] ?? null;
        $check_hash = $_SESSION['honey_pot'][$id]['honeypot_check_hash'] ?? null;

        $reason = null;
        if (!$checkTime) {
            $reason = 'missing_check_time';
        } elseif (!$check_hash) {
            $reason = 'missing_check_hash';
        } elseif(time() - $checkTime < self::MIN_SEC) {
            $reason = 'too_quick';
        }

        unset($_SESSION['honey_pot'][$id]);

        if ($reason) {
            throw new HoneypotException('spam check failed', reason: $reason);
        }
    }
}
