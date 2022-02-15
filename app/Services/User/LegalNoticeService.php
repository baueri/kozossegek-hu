<?php

namespace App\Services\User;

use App\QueryBuilders\UserLegalNotices;
use App\Models\UserLegacy;
use Framework\Http\Session;

class LegalNoticeService
{
    public function __construct(private UserLegalNotices $repo)
    {
    }

    public function setLegalNoticeSessionFor(?UserLegacy $user): void
    {
        if (!$user || Session::has('accepted_legal_notice_version')) {
            return;
        }

        $legalNotice = $this->repo
            ->forUser($user)
            ->first();

        Session::set('accepted_legal_notice_version', $legalNotice->accepted_legal_notice_version ?? 0);
    }

    public function updateOrInsertCurrentFor(UserLegacy $user): void
    {
        $this->repo->updateOrInsertCurrentFor($user);
        Session::set('accepted_legal_notice_version', LegalNoticeService::getVersion());
    }

    public static function getVersion(): int
    {
        return (int) config(APP_CFG_LEGAL_NOTICE_VERSION) ?? 0;
    }

    public static function needsApproval(): bool
    {
        return Session::get('accepted_legal_notice_version', '0') != self::getVersion();
    }
}
