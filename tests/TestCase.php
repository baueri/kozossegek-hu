<?php

declare(strict_types=1);

namespace Tests;

use App\QueryBuilders\ChurchGroups;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Fluent ChurchGroups mock that returns itself for chainable query methods.
     *
     * @return ChurchGroups&MockObject
     */
    protected function mockChurchGroupsQuery(int $countResult): ChurchGroups
    {
        $query = $this->createMock(ChurchGroups::class);
        $query->method('where')->willReturnSelf();
        $query->method('notDeleted')->willReturnSelf();
        $query->method('of')->willReturnSelf();
        $query->method('count')->willReturn($countResult);

        return $query;
    }
}
