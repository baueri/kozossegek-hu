<?php

declare(strict_types=1);

namespace App\Services\Captcha;

class NullCaptchaValidator implements CaptchaValidator
{
    public function validate(
        ?string $token,
        ?string $remoteIp = null
    ): void {
    }
}
