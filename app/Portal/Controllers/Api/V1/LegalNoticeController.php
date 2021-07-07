<?php

namespace App\Portal\Controllers\Api\V1;

use App\Auth\Auth;
use App\EntityQueryBuilders\UserLegalNotices;
use App\Services\User\LegalNoticeService;
use Framework\Http\Session;

class LegalNoticeController
{
    public function accept(): array
    {
        if ($user = Auth::user()) {
            UserLegalNotices::init()->updateOrInsertCurrentFor($user);
            Session::set('accepted_legal_notice_version', LegalNoticeService::getVersion());
        }

        return api()->ok();
    }
}
