<?php

namespace App\Portal\Controllers\Api\V1;

use App\Auth\Auth;
use App\EntityQueryBuilders\UserLegalNotices;

class LegalNoticeController
{
    public function accept()
    {
        if ($user = Auth::user()) {
            UserLegalNotices::init()->updateOrInsertCurrentFor($user);
        }

        return api()->ok();
    }
}
