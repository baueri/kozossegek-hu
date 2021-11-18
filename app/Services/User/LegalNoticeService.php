<?php

namespace App\Services\User;

use App\EntityQueryBuilders\UserLegalNotices;
use App\Models\User;
use Framework\Http\Session;

class LegalNoticeService
{
    private UserLegalNotices $repo;

    public function __construct(UserLegalNotices $repo)
    {
        $this->repo = $repo;
    }

    public function setLegalNoticeSessionFor(?User $user): void
    {
        if (!$user || Session::has('accepted_legal_notice_version')) {
            return;
        }

        $legalNotice = $this->repo
            ->forUser($user)
            ->first();

        Session::set('accepted_legal_notice_version', $legalNotice->accepted_legal_notice_version);
    }

    public function updateOrInsertCurrentFor(User $user): void
    {
        $this->repo->updateOrInsertCurrentFor($user);
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
