<?php

declare(strict_types=1);

namespace App\Portal\Controllers\Api\V1;

use App\Auth\Auth;
use App\Enums\AgeGroup;
use App\Enums\GroupStatus;
use App\Enums\JoinMode;
use App\Enums\OccasionFrequency;
use App\Enums\Tag;
use App\Enums\WeekDay;
use App\Models\ChurchGroup;
use App\Models\ChurchGroupView;
use App\Portal\Services\Api\GroupApiTransformer;
use App\Portal\Services\PortalCreateGroup;
use App\Portal\Services\PortalUpdateGroup;
use App\QueryBuilders\ChurchGroups;
use App\QueryBuilders\ChurchGroupViews;
use App\QueryBuilders\Institutes;
use App\QueryBuilders\SpiritualMovements;
use App\Services\GroupCreateGuard;
use App\Services\GroupSearchRepository;
use Exception;
use Framework\Exception\FileTypeNotAllowedException;
use Framework\Http\Controller;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Support\Collection;
use PDOException;
use Throwable;

class MeGroupsController extends Controller
{
    private const UPDATE_FIELDS = [
        'status',
        'name',
        'institute_id',
        'age_group',
        'occasion_frequency',
        'on_days',
        'spiritual_movement_id',
        'tags',
        'group_leaders',
        'description',
        'image',
        'join_mode',
    ];

    public function __construct(
        Request $request,
        private readonly GroupApiTransformer $transformer,
    ) {
        parent::__construct($request);
    }

    public function index(GroupSearchRepository $groupRepo): array
    {
        $groups = $groupRepo->getNotDeletedGroupsByUser(Auth::user());

        return api()->ok([
            'data' => $this->transformer->transformViews($groups),
        ]);
    }

    public function show(ChurchGroupViews $groupViews): array
    {
        $group = $this->findEditableView($groupViews);
        if (is_array($group)) {
            return $group;
        }

        $group->load('tags');

        return api()->ok([
            'data' => $this->transformer->transformView($group),
        ]);
    }

    public function store(
        PortalCreateGroup $createGroupService,
        Institutes $institutes,
        SpiritualMovements $spiritualMovements,
        GroupCreateGuard $createGuard
    ): array {
        try {
            $user = Auth::user();

            if ($createGuard->hasReachedHourlyRateLimit((int) $user->getId())) {
                return api()->error([
                    'message' => $createGuard->hourlyRateLimitMessage(),
                ], 429);
            }

            $payload = $this->normalizeWritablePayload($this->request->collect());
            $errors = $this->validateWritablePayload($payload, $institutes, $spiritualMovements, requireInstitute: true);
            if ($errors !== []) {
                return api()->error([
                    'message' => 'Validation failed',
                    'errors' => $errors,
                ], 422);
            }

            $group = $createGroupService->createGroup(
                $payload,
                $this->request->files['document'] ?? null,
                $user
            );

            if (!$group) {
                return api()->error([
                    'message' => 'Validation failed',
                    'errors' => $createGroupService->getErrors()->all(),
                ], 422);
            }

            $view = ChurchGroupViews::query()->with('tags')->find($group->getId());

            Response::setStatusCode(201);

            return api()->ok([
                'message' => 'Group created',
                'data' => $view
                    ? $this->transformer->transformView($view)
                    : $this->transformer->transformEntity($group),
            ]);
        } catch (FileTypeNotAllowedException) {
            return api()->error([
                'message' => 'Invalid document type. Allowed: doc, docx, pdf, jpeg, jpg, png',
            ], 422);
        } catch (PDOException $e) {
            report($e);

            return api()->error([
                'message' => 'Invalid field value. Check age_group, on_days, occasion_frequency, tags and institute_id.',
                'error' => env('DEBUG') ? $e->getMessage() : null,
            ], 422);
        } catch (Exception $e) {
            if ($createGroupService->hasErrors()) {
                return api()->error([
                    'message' => 'Validation failed',
                    'errors' => $createGroupService->getErrors()->all(),
                ], 422);
            }

            report($e);

            $payload = [
                'message' => 'Unexpected error',
            ];
            if (env('DEBUG')) {
                $payload['error'] = $e->getMessage();
            }

            return api()->error($payload, 500);
        }
    }

    public function update(PortalUpdateGroup $service, ChurchGroups $groups, ChurchGroupViews $groupViews, Institutes $institutes, SpiritualMovements $spiritualMovements): array
    {
        $group = $this->findEditableEntity($groups);
        if (is_array($group)) {
            return $group;
        }

        try {
            $group->load('tags');
            $payload = $this->normalizeWritablePayload(
                $this->mergeWithExistingGroup($group, collect($this->request->only(...self::UPDATE_FIELDS)))
            );
            $errors = $this->validateWritablePayload($payload, $institutes, $spiritualMovements, requireInstitute: false);
            if ($errors !== []) {
                return api()->error([
                    'message' => 'Validation failed',
                    'errors' => $errors,
                ], 422);
            }
            $service->update($group, $payload, $this->request->files['document'] ?? null);

            if ($service->hasErrors()) {
                return api()->error([
                    'message' => 'Validation failed',
                    'errors' => $service->getErrors()->all(),
                ], 422);
            }

            $view = $groupViews->with('tags')->find($group->getId());

            return api()->ok([
                'message' => 'Group updated',
                'data' => $view
                    ? $this->transformer->transformView($view)
                    : $this->transformer->transformEntity($group),
            ]);
        } catch (FileTypeNotAllowedException) {
            return api()->error([
                'message' => 'Invalid document type. Allowed: doc, docx, pdf, jpeg, jpg, png',
            ], 422);
        } catch (Throwable $e) {
            report($e);

            return api()->error(['message' => 'Unexpected error'], 500);
        }
    }

    public function destroy(ChurchGroups $groups): array
    {
        $group = $this->findEditableEntity($groups);
        if (is_array($group)) {
            return $group;
        }

        $groups->softDelete($group);

        return api()->ok(['message' => 'Group deleted']);
    }

    private function mergeWithExistingGroup(ChurchGroup $group, Collection $incoming): Collection
    {
        $defaults = [
            'status' => $group->status,
            'name' => $group->name,
            'institute_id' => $group->institute_id,
            'age_group' => array_values(array_filter(explode(',', (string) $group->age_group))),
            'occasion_frequency' => $group->occasion_frequency,
            'on_days' => array_values(array_filter(explode(',', (string) $group->on_days))),
            'spiritual_movement_id' => $group->spiritual_movement_id,
            'tags' => $group->tags ? $group->tags->map(fn ($tag) => $tag->tag)->values()->all() : [],
            'group_leaders' => $group->group_leaders,
            'description' => $group->description,
            'join_mode' => $group->join_mode,
        ];

        foreach ($defaults as $field => $value) {
            if (!$incoming->has($field)) {
                $incoming[$field] = $value;
            }
        }

        return $incoming;
    }

    private function normalizeWritablePayload(Collection $payload): Collection
    {
        foreach (['age_group', 'on_days', 'tags'] as $field) {
            if (!$payload->has($field) || $payload[$field] === null) {
                $payload[$field] = [];
                continue;
            }

            if (is_string($payload[$field])) {
                $payload[$field] = array_values(array_filter(array_map('trim', explode(',', $payload[$field]))));
            }

            if (!is_array($payload[$field])) {
                $payload[$field] = (array) $payload[$field];
            }
        }

        return $payload;
    }

    /**
     * @return array<string, array{message: string, allowed?: list<string>}|string>
     */
    private function validateWritablePayload(
        Collection $payload,
        Institutes $institutes,
        SpiritualMovements $spiritualMovements,
        bool $requireInstitute
    ): array {
        $errors = [];

        $ageGroups = array_values((array) ($payload['age_group'] ?? []));
        $invalidAgeGroups = array_values(array_diff($ageGroups, AgeGroup::values()));
        if ($invalidAgeGroups !== []) {
            $errors['age_group'] = $this->enumError(
                'Invalid value(s): ' . implode(', ', $invalidAgeGroups),
                AgeGroup::values()
            );
        } elseif ($requireInstitute && $ageGroups === []) {
            $errors['age_group'] = $this->enumError('At least one age_group is required.', AgeGroup::values());
        }

        $days = array_values((array) ($payload['on_days'] ?? []));
        $invalidDays = array_values(array_diff($days, WeekDay::values()));
        if ($invalidDays !== []) {
            $errors['on_days'] = $this->enumError(
                'Invalid value(s): ' . implode(', ', $invalidDays),
                WeekDay::values()
            );
        }

        if ($payload->has('occasion_frequency') && $payload['occasion_frequency'] !== null && $payload['occasion_frequency'] !== '') {
            if (!in_array((string) $payload['occasion_frequency'], OccasionFrequency::values(), true)) {
                $errors['occasion_frequency'] = $this->enumError(
                    'Invalid value: ' . $payload['occasion_frequency'],
                    OccasionFrequency::values()
                );
            }
        } elseif ($requireInstitute) {
            $errors['occasion_frequency'] = $this->enumError(
                'occasion_frequency is required.',
                OccasionFrequency::values()
            );
        }

        $tags = array_values((array) ($payload['tags'] ?? []));
        $invalidTags = array_values(array_diff($tags, Tag::values()));
        if ($invalidTags !== []) {
            $errors['tags'] = $this->enumError(
                'Invalid value(s): ' . implode(', ', $invalidTags),
                Tag::values()
            );
        }

        if ($payload->has('join_mode') && $payload['join_mode'] !== null && $payload['join_mode'] !== '') {
            if (!in_array((string) $payload['join_mode'], JoinMode::values(), true)) {
                $errors['join_mode'] = $this->enumError(
                    'Invalid value: ' . $payload['join_mode'],
                    JoinMode::values()
                );
            }
        }

        if ($payload->has('status') && $payload['status'] !== null && $payload['status'] !== '') {
            if (!in_array((string) $payload['status'], GroupStatus::values(), true)) {
                $errors['status'] = $this->enumError(
                    'Invalid value: ' . $payload['status'],
                    GroupStatus::values()
                );
            }
        }

        $instituteId = (int) ($payload['institute_id'] ?? 0);
        $newInstitute = $payload['institute'] ?? null;
        $hasNewInstitute = is_array($newInstitute)
            && trim((string) ($newInstitute['name'] ?? '')) !== ''
            && trim((string) ($newInstitute['leader_name'] ?? '')) !== ''
            && trim((string) ($newInstitute['city'] ?? '')) !== '';

        if ($requireInstitute) {
            if ($instituteId > 0) {
                if (!$institutes->find($instituteId)) {
                    $errors['institute_id'] = 'Institute not found. Use /api/v1/search-institute, or send an institute object to create one.';
                }
            } elseif (!$hasNewInstitute) {
                $errors['institute'] = 'Provide institute_id or institute object with name, leader_name and city.';
            }
        } elseif ($payload->has('institute_id') && ($instituteId <= 0 || !$institutes->find($instituteId))) {
            $errors['institute_id'] = 'Institute not found. Use /api/v1/search-institute to look up a valid id.';
        }

        if ($payload->has('spiritual_movement_id')
            && $payload['spiritual_movement_id'] !== null
            && $payload['spiritual_movement_id'] !== ''
        ) {
            $movementId = (int) $payload['spiritual_movement_id'];
            if ($movementId <= 0 || !$spiritualMovements->find($movementId)) {
                $errors['spiritual_movement_id'] = 'Spiritual movement not found. Use /api/v1/search-spiritual-movement to look up a valid id.';
            }
        }

        return $errors;
    }

    /**
     * @param list<string> $allowed
     * @return array{message: string, allowed: list<string>}
     */
    private function enumError(string $message, array $allowed): array
    {
        return [
            'message' => $message,
            'allowed' => array_values($allowed),
        ];
    }

    private function findEditableView(ChurchGroupViews $groupViews): ChurchGroupView|array
    {
        $group = $groupViews->find($this->request['id']);

        if (!$group || $group->isDeleted()) {
            return api()->error(['message' => 'Group not found'], 404);
        }

        if (!$group->isEditableBy(Auth::user())) {
            return api()->error(['message' => 'Forbidden'], 403);
        }

        return $group;
    }

    private function findEditableEntity(ChurchGroups $groups): ChurchGroup|array
    {
        $group = $groups->find($this->request['id']);

        if (!$group || $group->isDeleted()) {
            return api()->error(['message' => 'Group not found'], 404);
        }

        if (!$group->isEditableBy(Auth::user())) {
            return api()->error(['message' => 'Forbidden'], 403);
        }

        return $group;
    }
}
