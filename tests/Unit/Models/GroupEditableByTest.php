<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\ChurchGroup;
use App\Models\User;
use Tests\TestCase;

final class GroupEditableByTest extends TestCase
{
    public function testOwnerCanEdit(): void
    {
        $group = new ChurchGroup(['id' => 1, 'user_id' => 10]);
        $owner = new User(['id' => 10]);

        $this->assertTrue($group->isEditableBy($owner));
    }

    public function testOtherUserCannotEditWithoutManagedRelation(): void
    {
        $group = new ChurchGroup(['id' => 1, 'user_id' => 10]);
        $other = new User(['id' => 99]);

        // managed_church_groups lookup hits DB; without a matching row this is false.
        // Skip if DB is unreachable so unit runs stay local-friendly.
        try {
            db()->first('select 1');
        } catch (\Throwable) {
            $this->markTestSkipped('Database not available for managed_church_groups lookup.');
        }

        $this->assertFalse($group->isEditableBy($other));
    }

    public function testNullUserCannotEdit(): void
    {
        $group = new ChurchGroup(['id' => 1, 'user_id' => 10]);

        $this->assertFalse($group->isEditableBy(null));
    }

    public function testAdminFlagDoesNotBypassOwnershipCheck(): void
    {
        $group = new ChurchGroup(['id' => 1, 'user_id' => 10]);

        $admin = $this->getMockBuilder(User::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isAdmin'])
            ->getMock();
        $admin->id = 50;
        $admin->method('isAdmin')->willReturn(true);

        // Even if isAdmin() would be true, isEditableBy must not use it anymore.
        // Direct assertion on the trait logic for non-owner without DB: owner id mismatch
        // and managed lookup short-circuits only if DB available.
        try {
            db()->first('select 1');
        } catch (\Throwable) {
            $this->assertFalse($admin->id == $group->user_id);
            $this->markTestSkipped('Database not available for managed_church_groups lookup.');
        }

        $this->assertFalse($group->isEditableBy($admin));
    }
}
