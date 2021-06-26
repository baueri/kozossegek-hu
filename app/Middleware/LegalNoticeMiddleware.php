<?php

namespace App\Middleware;

use App\Auth\Auth;
use App\EntityQueryBuilders\Notifications;
use App\EntityQueryBuilders\UserLegalNotices;
use App\Models\Notification;
use Framework\Http\View\View;
use Framework\Middleware\Middleware;

class LegalNoticeMiddleware implements Middleware
{
    public function handle(): void
    {
        $needsAcceptance = $this->needsAcceptance();

        if ($needsAcceptance && request()->exists('accept-legal-notice')) {
            UserLegalNotices::init()->updateOrInsertCurrentFor(Auth::user());
            return;
        }

        if (Auth::user() && $needsAcceptance) {
            View::setVariable('display_legal_notice', true);
        }
    }
    private function needsAcceptance(): bool
    {
        return !UserLegalNotices::init()
            ->forCurrentUser()
            ->currentVersion()
            ->exists();
    }
}
