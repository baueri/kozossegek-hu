<?php

namespace App\Middleware;

use App\Auth\Auth;
use App\EntityQueryBuilders\UserLegalNotices;
use App\Services\User\LegalNoticeService;
use Framework\Http\View\View;
use Framework\Middleware\Middleware;

class LegalNoticeMiddleware implements Middleware
{
    public function handle(): void
    {
        $needsAcceptance = LegalNoticeService::needsApproval();

        if (($user = Auth::user()) && $needsAcceptance && request()->exists('accept-legal-notice')) {
            UserLegalNotices::init()->updateOrInsertCurrentFor($user);
            return;
        }

        if (Auth::user() && $needsAcceptance) {
            View::setVariable('display_legal_notice', true);
            View::setVariable('legal_notice_date', date('Y.m.d', strtotime(config(APP_CFG_LEGAL_NOTICE_DATE))));
        }
    }
}
