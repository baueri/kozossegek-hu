<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Models\User;
use App\Models\UserLegalNotice;
use App\Services\User\LegalNoticeService;
use Framework\Model\EntityQueryBuilder;

/**
 * @phpstan-extends \Framework\Model\EntityQueryBuilder<\App\Models\UserLegalNotice>
 */
class UserLegalNotices extends EntityQueryBuilder
{
    public static function getModelClass(): string
    {
        return UserLegalNotice::class;
    }

    public function forUser(User $user): self
    {
        return $this->where('user_id', $user->id);
    }

    public function updateOrInsertCurrentFor(User $user)
    {
        $this->updateOrInsert(
            ['user_id' => $user->id],
            ['accepted_legal_notice_version' => LegalNoticeService::getVersion()]
        );
    }
}
