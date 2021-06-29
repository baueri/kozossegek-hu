<?php

namespace App\Services\User;

use App\EntityQueryBuilders\UserLegalNotices;
use App\Models\User;
use Framework\Http\Session;

class LegalNoticeService
{
    public function setLegalNoticeSessionFor(User $user): void
    {
        if (!self::needsCheck()) {
            return;
        }

        $hasAcceptedLegalNotice = UserLegalNotices::init()
            ->forUser($user)
            ->currentVersion()
            ->exists();

        Session::set('needs_legal_notice_accept', $hasAcceptedLegalNotice === false);
    }

    public static function getVersion(): ?int
    {
        return config(APP_CFG_LEGAL_NOTICE_VERSION);
    }

    public static function needsCheck(): bool
    {
        return self::getVersion() > 0;
    }
}
