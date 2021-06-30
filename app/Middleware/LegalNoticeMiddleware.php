<?php

namespace App\Middleware;

use App\Auth\Auth;
use App\EntityQueryBuilders\UserLegalNotices;
use App\Services\User\LegalNoticeService;
use Framework\Http\Session;
use Framework\Http\View\View;
use Framework\Middleware\Middleware;

class LegalNoticeMiddleware implements Middleware
{
    public function handle(): void
    {
        if (($needsAcceptance = LegalNoticeService::needsApproval()) && request()->exists('accept-legal-notice')) {
            UserLegalNotices::init()->updateOrInsertCurrentFor(Auth::user());
            return;
        }

        if (Auth::user() && $needsAcceptance) {
            View::setVariable('display_legal_notice', true);
        }
    }
}
