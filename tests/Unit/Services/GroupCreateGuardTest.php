<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Services\GroupCreateGuard;
use Tests\TestCase;

final class GroupCreateGuardTest extends TestCase
{
    public function testMaxPendingGroupsComesFromConfig(): void
    {
        $guard = new GroupCreateGuard(fn () => $this->mockChurchGroupsQuery(0));

        $this->assertSame(20, $guard->maxPendingGroups());
    }

    public function testMaxGroupsPerHourComesFromConfig(): void
    {
        $guard = new GroupCreateGuard(fn () => $this->mockChurchGroupsQuery(0));

        $this->assertSame(20, $guard->maxGroupsPerHour());
    }

    public function testHasReachedPendingLimitWhenCountEqualsMax(): void
    {
        $guard = new GroupCreateGuard(fn () => $this->mockChurchGroupsQuery(20));

        $this->assertTrue($guard->hasReachedPendingLimit(42));
    }

    public function testHasReachedPendingLimitWhenCountAboveMax(): void
    {
        $guard = new GroupCreateGuard(fn () => $this->mockChurchGroupsQuery(21));

        $this->assertTrue($guard->hasReachedPendingLimit(42));
    }

    public function testHasNotReachedPendingLimitWhenBelowMax(): void
    {
        $guard = new GroupCreateGuard(fn () => $this->mockChurchGroupsQuery(19));

        $this->assertFalse($guard->hasReachedPendingLimit(42));
    }

    public function testHasReachedHourlyRateLimitWhenCountEqualsMax(): void
    {
        $guard = new GroupCreateGuard(fn () => $this->mockChurchGroupsQuery(20));

        $this->assertTrue($guard->hasReachedHourlyRateLimit(42));
    }

    public function testHasReachedHourlyRateLimitWhenCountAboveMax(): void
    {
        $guard = new GroupCreateGuard(fn () => $this->mockChurchGroupsQuery(21));

        $this->assertTrue($guard->hasReachedHourlyRateLimit(42));
    }

    public function testHasNotReachedHourlyRateLimitWhenBelowMax(): void
    {
        $guard = new GroupCreateGuard(fn () => $this->mockChurchGroupsQuery(19));

        $this->assertFalse($guard->hasReachedHourlyRateLimit(42));
    }

    public function testPendingLimitMessageMentionsConfiguredMax(): void
    {
        $guard = new GroupCreateGuard(fn () => $this->mockChurchGroupsQuery(0));

        $this->assertStringContainsString('20', $guard->pendingLimitMessage());
        $this->assertStringContainsString('jóváhagyásra váró', $guard->pendingLimitMessage());
    }

    public function testHourlyRateLimitMessageMentionsConfiguredMax(): void
    {
        $guard = new GroupCreateGuard(fn () => $this->mockChurchGroupsQuery(0));

        $this->assertStringContainsString('20', $guard->hourlyRateLimitMessage());
        $this->assertStringContainsString('Rate limit exceeded', $guard->hourlyRateLimitMessage());
    }

    public function testCountPendingGroupsDelegatesToQuery(): void
    {
        $guard = new GroupCreateGuard(fn () => $this->mockChurchGroupsQuery(2));

        $this->assertSame(2, $guard->countPendingGroups(7));
    }

    public function testCountGroupsCreatedLastHourDelegatesToQuery(): void
    {
        $guard = new GroupCreateGuard(fn () => $this->mockChurchGroupsQuery(4));

        $this->assertSame(4, $guard->countGroupsCreatedLastHour(7));
    }
}
