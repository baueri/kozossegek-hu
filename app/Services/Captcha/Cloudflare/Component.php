<?php

declare(strict_types=1);

namespace App\Services\Captcha\Cloudflare;

class Component extends \Framework\Http\View\Component
{
    public function __construct(
        private readonly Config $config
    ) {
    }

    public function render(): string
    {
        $siteKey = $this->config->siteKey;

        $widgetId = 'cf-' . bin2hex(random_bytes(3));

        return <<<HTML
            <div
                id="{$widgetId}"
                class="cf-turnstile"
                data-sitekey="{$siteKey}"
                data-theme="light"
                data-size="normal"
                data-callback="cf_onSuccess"
                data-error-callback="cf_onError"
                data-expired-callback="cf_onExpired"
            ></div>
            <input type="hidden" name="cft" />
            <script>
                const cf_wid = "#{$widgetId}";
                (() => {
                    window.cf_onSuccess = function (token) {
                        $("[name='cft']").val(token);
                    };

                    window.cf_onError = () => {
                        $("[name='cft']").val('');
                    };

                    window.cf_onExpired = () => {
                        $("[name='cft']").val('');
                        turnstile.reset(cf_wid);
                    };
                })();
            </script>
        HTML;
    }
}