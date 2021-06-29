<?php

namespace App\Services\User;

use App\EntityQueryBuilders\UserLegalNotices;
use App\Models\User;
use Framework\Http\Session;

class LegalNoticeService
{
    public function setLegalNoticeSessionFor(User $user): void
    {
        $legalNotice = UserLegalNotices::init()
            ->forUser($user)
            ->first();

        Session::set('accepted_legal_notice_version', $legalNotice->accepted_legal_notice_version);
    }

    public static function getVersion(): ?int
    {
        return config(APP_CFG_LEGAL_NOTICE_VERSION);
    }

    public static function needsCheck(): bool
    {
        return Session::get('accepted_legal_notice_version', 0) !== self::getVersion();
    }
}
