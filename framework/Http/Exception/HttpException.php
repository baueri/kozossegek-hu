<?php

declare(strict_types=1);

namespace Framework\Http\Exception;

use Exception;
use Throwable;

class HttpException extends Exception
{
    public function __construct(string $message = "", int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}