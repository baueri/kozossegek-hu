<?php

declare(strict_types=1);

namespace Tests\Unit\Admin\Group;

use App\Admin\Group\Services\CreateGroup;
use App\Models\ChurchGroup;
use App\Models\Institute;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\Institutes;
use App\Services\GroupCreateGuard;
use App\Services\RebuildSearchEngine;
use Framework\File\FileManager;
use Framework\Support\Collection;
use Tests\TestCase;

final class CreateGroupInstituteAllowlistTest extends TestCase
{
    public function testFilterInstitutePayloadKeepsOnlyAllowedFields(): void
    {
        $service = $this->makeService(
            createGuard: new GroupCreateGuard(fn () => $this->mockChurchGroupsQuery(0)),
            institutes: $this->createMock(Institutes::class),
            repository: $this->createMock(ChurchGroups::class),
        );

        $filtered = $service->filterInstitutePayload([
            'name' => 'Szent József templom',
            'leader_name' => 'Nagy János',
            'city' => 'Budapest',
            'district' => 'II.',
            'address' => 'Fő utca 1.',
            'miserend_id' => 99999,
            'approved' => 1,
            'website' => 'https://evil.example',
            'image_url' => 'https://evil.example/x.jpg',
            'deleted_at' => '2020-01-01 00:00:00',
            'user_id' => 123,
        ]);

        $this->assertSame([
            'name' => 'Szent József templom',
            'leader_name' => 'Nagy János',
            'city' => 'Budapest',
            'district' => 'II.',
            'address' => 'Fő utca 1.',
        ], $filtered);
        $this->assertArrayNotHasKey('miserend_id', $filtered);
        $this->assertArrayNotHasKey('approved', $filtered);
        $this->assertArrayNotHasKey('website', $filtered);
        $this->assertArrayNotHasKey('image_url', $filtered);
        $this->assertArrayNotHasKey('deleted_at', $filtered);
        $this->assertArrayNotHasKey('user_id', $filtered);
    }

    public function testCreateStripsDisallowedInstituteFieldsBeforeInsert(): void
    {
        $capturedInstitute = null;

        $instituteEntity = new Institute();
        $instituteEntity->id = 55;

        $institutes = $this->createMock(Institutes::class);
        $institutes->expects($this->once())
            ->method('create')
            ->willReturnCallback(function (array $payload) use (&$capturedInstitute, $instituteEntity) {
                $capturedInstitute = $payload;
                return $instituteEntity;
            });

        $group = new ChurchGroup();
        $group->id = 10;

        $repository = $this->createMock(ChurchGroups::class);
        $repository->expects($this->once())->method('create')->willReturn($group);
        $repository->expects($this->once())->method('save')->willReturn(1);

        $service = new class (
            $repository,
            $this->createMock(RebuildSearchEngine::class),
            $this->createMock(FileManager::class),
            $institutes,
            new GroupCreateGuard(fn () => $this->mockChurchGroupsQuery(0)),
        ) extends CreateGroup {
            protected function syncTags(ChurchGroup $group, array $tags = []): void
            {
            }

            protected function updateSearchEngine(ChurchGroup $group): void
            {
            }

            protected function syncImages(ChurchGroup $group, $images)
            {
            }
        };

        $result = $service->create(collect([
            'name' => 'Teszt közösség',
            'description' => 'Leírás',
            'group_leaders' => 'Kovács Péter',
            'age_group' => ['fiatal_felnott'],
            'occasion_frequency' => 'hetente',
            'user_id' => 7,
            'institute' => [
                'name' => 'Új templom',
                'leader_name' => 'Plébános',
                'city' => 'Debrecen',
                'district' => '',
                'address' => 'Kossuth 1.',
                'miserend_id' => 4242,
                'approved' => 1,
                'website' => 'https://attacker.test',
            ],
        ]));

        $this->assertInstanceOf(ChurchGroup::class, $result);
        $this->assertNotNull($capturedInstitute);
        $this->assertSame('Új templom', $capturedInstitute['name']);
        $this->assertSame('Plébános', $capturedInstitute['leader_name']);
        $this->assertSame('Debrecen', $capturedInstitute['city']);
        $this->assertSame(0, $capturedInstitute['approved']);
        $this->assertSame(7, $capturedInstitute['user_id']);
        $this->assertArrayNotHasKey('miserend_id', $capturedInstitute);
        $this->assertArrayNotHasKey('website', $capturedInstitute);
    }

    public function testCreateRejectsWhenPendingLimitReached(): void
    {
        $institutes = $this->createMock(Institutes::class);
        $institutes->expects($this->never())->method('create');

        $repository = $this->createMock(ChurchGroups::class);
        $repository->expects($this->never())->method('create');

        $service = $this->makeService(
            createGuard: new GroupCreateGuard(fn () => $this->mockChurchGroupsQuery(20)),
            institutes: $institutes,
            repository: $repository,
        );

        $result = $service->create(collect([
            'name' => 'Teszt közösség',
            'description' => 'Leírás',
            'group_leaders' => 'Kovács Péter',
            'age_group' => ['fiatal_felnott'],
            'occasion_frequency' => 'hetente',
            'user_id' => 7,
            'institute_id' => 1,
        ]));

        $this->assertNull($result);
        $this->assertTrue($service->hasErrors());
        $this->assertStringContainsString('20', (string) $service->firstError());
    }

    public function testCreateAllowsWhenPendingBelowLimit(): void
    {
        $group = new ChurchGroup();
        $group->id = 11;

        $repository = $this->createMock(ChurchGroups::class);
        $repository->expects($this->once())->method('create')->willReturn($group);
        $repository->expects($this->once())->method('save')->willReturn(1);

        $service = new class (
            $repository,
            $this->createMock(RebuildSearchEngine::class),
            $this->createMock(FileManager::class),
            $this->createMock(Institutes::class),
            new GroupCreateGuard(fn () => $this->mockChurchGroupsQuery(2)),
        ) extends CreateGroup {
            protected function syncTags(ChurchGroup $group, array $tags = []): void
            {
            }

            protected function updateSearchEngine(ChurchGroup $group): void
            {
            }

            protected function syncImages(ChurchGroup $group, $images)
            {
            }
        };

        $result = $service->create(collect([
            'name' => 'Teszt közösség',
            'description' => 'Leírás',
            'group_leaders' => 'Kovács Péter',
            'age_group' => ['fiatal_felnott'],
            'occasion_frequency' => 'hetente',
            'user_id' => 7,
            'institute_id' => 15,
        ]));

        $this->assertInstanceOf(ChurchGroup::class, $result);
        $this->assertFalse($service->hasErrors());
    }

    private function makeService(
        GroupCreateGuard $createGuard,
        Institutes $institutes,
        ChurchGroups $repository,
    ): CreateGroup {
        return new class (
            $repository,
            $this->createMock(RebuildSearchEngine::class),
            $this->createMock(FileManager::class),
            $institutes,
            $createGuard,
        ) extends CreateGroup {
            protected function syncTags(ChurchGroup $group, array $tags = []): void
            {
            }

            protected function updateSearchEngine(ChurchGroup $group): void
            {
            }

            protected function syncImages(ChurchGroup $group, $images)
            {
            }
        };
    }
}
