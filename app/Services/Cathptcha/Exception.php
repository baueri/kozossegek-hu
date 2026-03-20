<?php

declare(strict_types=1);

namespace App\Services\Cathptcha;

use Framework\Exception\UnauthorizedException;
use Throwable;

class Exception extends UnauthorizedException
{
    public function __construct(
        $message = "",
        $code = 401,
        Throwable $previous = null,
        public readonly ?string $question,
        public readonly ?string $answer,
    ) {
        $message = $message ?: 'Captcha answer is incorrect. q: ' . $question . ' a: ' . $answer;
        parent::__construct($message, $code, $previous);
    }
}