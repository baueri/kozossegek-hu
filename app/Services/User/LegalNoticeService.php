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

    public static function getVersion(): int
    {
        return (int) config(APP_CFG_LEGAL_NOTICE_VERSION) ?? 0;
    }

    public static function needsApproval(): bool
    {
        $version = self::getVersion();
        return $version > 0 && Session::get('accepted_legal_notice_version', 0) !== $version;
    }
}
