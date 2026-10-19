<?php

declare(strict_types=1);

namespace App\View\Mint\Components;

use Baueri\Mint\Context;
use Baueri\Mint\Module\Module;

/**
 * Login prompt modal shell: title + optional icon + slot (subtitle HTML) + shared login form.
 */
class Modal extends Module
{
    public function render(Context $context): string
    {
        $modalId = (string) ($context->resolve('id') ?? 'login-prompt-modal');
        $modalId = preg_replace('/[^a-zA-Z0-9_-]/', '', $modalId) ?: 'login-prompt-modal';

        $title = (string) $context->resolve('title', '');

        $iconRaw = trim((string) ($context->resolve('icon') ?? ''));
        $icon = $iconRaw !== ''
            ? preg_replace('/[^a-z0-9-]/i', '', $iconRaw) ?: 'sign-in-alt'
            : 'sign-in-alt';

        $redirect = (string) ($context->resolve('redirect') ?? '');
        if ($redirect === '') {
            $redirect = request()->uri();
        }

        $emailId = $modalId . '-email';
        $passwordId = $modalId . '-password';

        return $this->view($context, 'components/modal.php', [
            'modalId' => $modalId,
            'title' => $title,
            'icon' => $icon,
            'redirect' => $redirect,
            'loginAction' => route('doLogin'),
            'forgotUrl' => route('portal.forgot_password'),
            'registerUrl' => route('portal.register'),
            'emailId' => $emailId,
            'passwordId' => $passwordId,
        ]);
    }
}
