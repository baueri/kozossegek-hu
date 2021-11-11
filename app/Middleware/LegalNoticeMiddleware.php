<?php

namespace App\Middleware;

use App\Auth\Auth;
use App\Services\User\LegalNoticeService;
use Framework\Http\View\View;
use Framework\Middleware\Middleware;

class LegalNoticeMiddleware implements Middleware
{
    private LegalNoticeService $service;

    public function __construct(LegalNoticeService $service)
    {
        $this->service = $service;
    }

    public function handle(): void
    {
        $user = Auth::user();
        $this->service->setLegalNoticeSessionFor($user);

        $needsAcceptance = LegalNoticeService::needsApproval();

        if ($user && $needsAcceptance) {
            if (request()->exists('accept-legal-notice')) {
                $this->service->updateOrInsertCurrentFor($user);
                return;
            }
            View::setVariable('display_legal_notice', true);
            View::setVariable('legal_notice_date', date('Y.m.d', strtotime(config(APP_CFG_LEGAL_NOTICE_DATE))));
        }
    }
}
