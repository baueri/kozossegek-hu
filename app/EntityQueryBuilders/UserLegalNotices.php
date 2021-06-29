<?php

namespace App\EntityQueryBuilders;

use App\Auth\Auth;
use App\Models\Traits\HasUserColumn;
use App\Models\User;
use App\Models\UserLegalNotice;
use App\Services\User\LegalNoticeService;
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
        return $this->forUser(Auth::user());
    }

    public function forUser(User $user): self
    {
        return $this->where('user_id', $user->id);
    }

    public function currentVersion(): self
    {
        return $this->where(
            'accepted_legal_notice_version',
            LegalNoticeService::getVersion()
        );
    }

    public function updateOrInsertCurrentFor(User $user)
    {
        return $this->updateOrInsert([
            'user_id' => $user->id
        ], [
            'accepted_legal_notice_version' => LegalNoticeService::getVersion(),
        ]);
    }
}
