<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\GroupPending;
use App\QueryBuilders\ChurchGroups;
use Closure;

final class GroupCreateGuard
{
    /** @var Closure():ChurchGroups */
    private Closure $queryFactory;

    /**
     * @param Closure():ChurchGroups|null $queryFactory
     */
    public function __construct(?Closure $queryFactory = null)
    {
        $this->queryFactory = $queryFactory ?? static fn (): ChurchGroups => ChurchGroups::query();
    }

    public function maxPendingGroups(): int
    {
        return (int) config('api.max_pending_groups', 3);
    }

    public function maxGroupsPerHour(): int
    {
        return (int) config('api.max_groups_per_hour', 10);
    }

    public function countPendingGroups(int $userId): int
    {
        return ($this->queryFactory)()
            ->where('user_id', $userId)
            ->where('pending', GroupPending::pending)
            ->notDeleted()
            ->count();
    }

    public function countGroupsCreatedLastHour(int $userId): int
    {
        return ($this->queryFactory)()
            ->where('user_id', $userId)
            ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-1 hour')))
            ->count();
    }

    public function hasReachedPendingLimit(int $userId): bool
    {
        return $this->countPendingGroups($userId) >= $this->maxPendingGroups();
    }

    public function hasReachedHourlyRateLimit(int $userId): bool
    {
        return $this->countGroupsCreatedLastHour($userId) >= $this->maxGroupsPerHour();
    }

    public function pendingLimitMessage(): string
    {
        $max = $this->maxPendingGroups();

        return "Legfeljebb {$max} jóváhagyásra váró közösséged lehet. Várj a jóváhagyásra, vagy töröld valamelyik függő közösséget.";
    }

    public function hourlyRateLimitMessage(): string
    {
        $max = $this->maxGroupsPerHour();

        return "Rate limit exceeded. Max {$max} groups per hour.";
    }
}
