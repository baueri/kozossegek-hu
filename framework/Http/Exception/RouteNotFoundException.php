<?php

namespace Framework\Http\Exception;

class RouteNotFoundException extends \Exception
{
    public function __construct(string $message = "", int $code = 404, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
