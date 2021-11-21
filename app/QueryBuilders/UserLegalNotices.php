<?php

namespace App\QueryBuilders;

use App\Auth\Auth;
use App\Models\Traits\HasUserColumn;
use App\Models\User;
use App\Models\UserLegalNotice;
use App\Services\User\LegalNoticeService;
use Framework\Model\EntityQueryBuilder;

/**
 * @phpstan-extends \Framework\Model\EntityQueryBuilder<\App\Models\UserLegalNotice>
 */
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

    public function updateOrInsertCurrentFor(User $user): int
    {
        return $this->updateOrInsert([
            'user_id' => $user->id
        ], [
            'accepted_legal_notice_version' => LegalNoticeService::getVersion(),
        ]);
    }
}
