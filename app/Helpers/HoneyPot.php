<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Exception\HoneypotException;

class HoneyPot
{
    public const int MIN_SEC = 5;

    public static function setTime(string $id): void
    {
        unset($_SESSION['hp_check_time'][$id]);
        $_SESSION['hp_check_time'][$id] = time();
    }

    /**
     * @throws HoneypotException
     */
    public static function validate(string $id, string $hashVal): void
    {
        if ($hashVal) {
            throw new HoneypotException('spam check failed', reason: 'filled_honeypot');
        }

        $checkTime = $_SESSION['hp_check_time'][$id] ?? null;
        $elapsedTime = $checkTime ? time() - $checkTime : null;
        $reason = null;
        if (!$checkTime) {
            $reason = 'missing_check_time';
        } elseif($elapsedTime < self::MIN_SEC) {
            $reason = 'too_quick';
        }

        self::setTime($id);

        if ($reason) {
            throw new HoneypotException('spam check failed', reason: $reason, elapsedTime: $elapsedTime);
        }
    }
}
