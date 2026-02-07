<?php

declare(strict_types=1);

namespace App\Services\Captcha;

interface CaptchaValidator
{
    /**
     * @throws Exception
     */
    public function validate(string $token, ?string $remoteIp = null): void;
}