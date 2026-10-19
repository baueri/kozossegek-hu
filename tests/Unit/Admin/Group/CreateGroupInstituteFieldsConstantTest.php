<?php

declare(strict_types=1);

namespace Tests\Unit\Admin\Group;

use App\Admin\Group\Services\CreateGroup;
use Tests\TestCase;

final class CreateGroupInstituteFieldsConstantTest extends TestCase
{
    public function testInstituteAllowlistMatchesAdminCreateFields(): void
    {
        $this->assertSame(
            ['name', 'city', 'district', 'address', 'leader_name'],
            CreateGroup::INSTITUTE_FIELDS
        );
    }
}
