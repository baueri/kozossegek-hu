<?php

declare(strict_types=1);

namespace Tests\Unit\Portal\Api;

use App\Portal\Controllers\Api\V1\MeGroupsController;
use App\Services\GroupCreateGuard;
use ReflectionMethod;
use Tests\TestCase;

/**
 * Controller HTTP header side effects are awkward under PHPUnit output.
 * Rate-limit behavior is covered in GroupCreateGuardTest; here we only
 * assert that store() is wired to the guard.
 */
final class MeGroupsControllerRateLimitTest extends TestCase
{
    public function testStoreAcceptsGroupCreateGuardDependency(): void
    {
        $method = new ReflectionMethod(MeGroupsController::class, 'store');
        $params = $method->getParameters();
        $types = array_map(
            static fn ($param) => $param->getType()?->getName(),
            $params
        );

        $this->assertContains(GroupCreateGuard::class, $types);
    }
}
