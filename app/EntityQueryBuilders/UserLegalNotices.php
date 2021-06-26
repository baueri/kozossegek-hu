<?php

namespace App\EntityQueryBuilders;

use App\Auth\Auth;
use App\Models\Traits\HasUserColumn;
use App\Models\User;
use App\Models\UserLegalNotice;
use Framework\Model\EntityQueryBuilder;

class UserLegalNotices extends EntityQueryBuilder
{
    use HasUserColumn;

    protected static function getModelClass(): string
    {
        return UserLegalNotice::class;
    }

    public function forCurrentUser(): self
    {
        return $this->where('user_id', Auth::user()->id);
    }

    public function currentVersion(): self
    {
        return $this->where(
            'accepted_legal_notice_version',
            config(APP_CFG_LEGAL_NOTICE_VERSION)
        );
    }

    public function updateOrInsertCurrentFor(User $user)
    {
        return $this->updateOrInsert([
            'user_id' => $user->id
        ], [
            'accepted_legal_notice_version' => config(APP_CFG_LEGAL_NOTICE_VERSION),
        ]);
    }
}
