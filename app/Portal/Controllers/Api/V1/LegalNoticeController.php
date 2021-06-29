<?php

namespace App\Portal\Controllers\Api\V1;

use App\Auth\Auth;
use App\EntityQueryBuilders\UserLegalNotices;
use Framework\Http\Session;

class LegalNoticeController
{
    public function accept()
    {
        if ($user = Auth::user()) {
            UserLegalNotices::init()->updateOrInsertCurrentFor($user);
            Session::set('needs_legal_notice_accept', false);
        }

        return api()->ok();
    }
}
