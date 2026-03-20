<?php

declare(strict_types=1);

namespace App\Services\Captcha;

use Throwable;

class Exception extends \Exception
{
    public function __construct(string $message = "", int $code = 400, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}