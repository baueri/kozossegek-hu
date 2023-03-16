<?php

declare(strict_types=1);

namespace Framework\Exception;

use Framework\Http\Exception\HttpException;
use Throwable;

class UnauthorizedException extends HttpException
{
    public function __construct($message = "", $code = 401, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
