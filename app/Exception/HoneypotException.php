<?php

declare(strict_types=1);

namespace App\Exception;

use Framework\Exception\UnauthorizedException;
use Throwable;

class HoneypotException extends UnauthorizedException
{
    public function __construct($message = "", $code = 401, Throwable $previous = null, public readonly string $reason = '')
    {
        parent::__construct($message, $code, $previous);
    }
}
