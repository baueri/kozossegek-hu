<?php

declare(strict_types=1);

namespace Framework\Http\Exception;

use InvalidArgumentException;
use Throwable;

class RequestParameterException extends InvalidArgumentException
{
    public function __construct($message = "", $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
