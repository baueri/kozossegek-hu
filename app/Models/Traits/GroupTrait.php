<?php

namespace App\Models\Traits;

use App\Enums\AgeGroup;
use App\Enums\Denomination;
use App\Enums\OccasionFrequency;
use App\Enums\GroupPending;
use App\Enums\WeekDay;
use App\Enums\GroupStatus;
use App\Enums\JoinMode;
use App\Models\ChurchGroupView;
use App\Models\Institute;
use App\Portal\BreadCrumb\BreadCrumb;
use App\Helpers\GroupHelper;
use App\Models\GroupComment;
use App\Models\GroupTag;
use App\Models\User;
use App\Services\SystemAdministration\SiteMap\EntitySiteMappable;
use Framework\File\File;
use Framework\Model\HasTimestamps;
use Framework\Support\Collection;
use Framework\Support\StringHelper;

/**
 * @property-read ?Institute $institute
 * @property-read ?Collection<GroupTag> $tags
 * @property-read ?GroupComment $comment
 */
trait GroupTrait
{
    use EntitySiteMappable;
    use HasTimestamps;

    private ?string $cachedUrl = null;

    public function ageGroup(): string
    {
        if ($this->getAgeGroups()->count() > 1) {
            return lang('age_group.vegyes');
        }

        return (string) $this->getAgeGroups()->first()?->translate();
    }

    public function allAgeGroupsAsString(): string
    {
        return $this->getAgeGroups()->map->translate()->implode(', ');
    }

    public function getDaysAsString(): string
    {
        return $this->getDays()->map->translate()->implode(', ');
    }

    /**
     * @return Collection<AgeGroup>
     */
    public function getAgeGroups(): Collection
    {
        return Collection::fromList($this->age_group)->as(AgeGroup::class);
    }

    public function denomination(): string
    {
        return Denomination::from($this->denomination)->translate();
    }

    /**
     * @return Collection<WeekDay>
     */
    public function getDays(): Collection
    {
        return Collection::fromList($this->on_days)->as(WeekDay::class);
    }

    public function occasionFrequency(): string
    {
        return OccasionFrequency::from($this->occasion_frequency)->translate();
    }

    public function excerpt(int $words = 25): string
    {
        return StringHelper::more(strip_tags($this->description), $words, '...');
    }

    public function slug(): string
    {
        return StringHelper::slugify($this->name . '-' . $this->id);
    }

    public function getStorageImageDir(): string
    {
        return GroupHelper::getStoragePath($this->id);
    }

    public function joinMode(): ?JoinMode
    {
        if (!$this->join_mode) {
            return null;
        }
        return JoinMode::from($this->join_mode);
    }

    public function joinModeText(): ?string
    {
        return $this->joinMode()?->translate();
    }

    public function isVisibleBy(?User $user): bool
    {
        if ($user && ($user->isAdmin() || $this->user_id == $user->id)) {
            return true;
        }

        if ($this->pending == 0 && $this->status == GroupStatus::active->value) {
            return true;
        }

        return false;
    }

    public function getEditUrl(): string
    {
        return route('portal.edit_group', $this);
    }

    public function isEditableBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        // todo relációval megoldani a szerkesztési jogosultság ellenőrzést
        return $user->isAdmin() || $user->id == $this->user_id || builder('managed_church_groups')->where('group_id', $this->id)->where('user_id', $user->id)->exists();
    }

    public function hasDocument(): bool
    {
        return file_exists($this->getDocumentPath());
    }

    public function getDocument(): ?File
    {
        return new File($this->getDocumentPath());
    }

    public function getDocumentPath(): string
    {
        if (!$this->document) {
            return '';
        }

        return GroupHelper::getStoragePath($this->id) . $this->document;
    }

    public function getDocumentUrl(): string
    {
        return "/my-group/{$this->id}/download-document";
    }

    public function isRejected(): bool
    {
        return $this->pending == -1;
    }

    public function pendingStatusIs(int|GroupPending $status): bool
    {
        $status = $status instanceof GroupPending ? (int) $status->value() : $status;

        if (is_null($this->pending)) {
            return false;
        }

        return (int) $this->pending === $status;
    }

    public function url(): string
    {
        return $this->cachedUrl ??= route('kozosseg', ['kozosseg' => $this->slug()]);
    }

    public function getUrl(): string
    {
        return $this->url();
    }

    public function getBreadCrumb(): BreadCrumb
    {
        $breadCrumbs = [
            ['name' => 'Közösségek', 'position' => 1, 'url' => route('portal.groups')],
            [
                'name' => $this->city,
                'position' => 2,
                'url' => route('portal.groups', ['varos' => $this->city])
            ],
            [
                'name' => $this->institute_name,
                'position' => 3,
                'url' => $this->institute?->groupsUrl()
            ],
            [
                'name' => $this->name,
                'position' => 4
            ]
        ];

        return new BreadCrumb($breadCrumbs);
    }

    public function toMeiliSearch(): array
    {
        $data = $this->only([
            'id',
            'name',
            'city',
            'institute_name',
            'institute_name2',
            'spiritual_movement',
            'group_leaders',
            'description',
            'institute_id',
            'confirmed_at',
            'spiritual_movement_id',
            'district',
            'image_url'
        ]);

        $data['age_group'] = $this->getAgeGroups()->pluck('name')->all();
        $data['age_group_text'] = $this->getAgeGroups()->map->translate()->all();
        $data['join_mode'] = $this->joinModeText();
        $data['on_days'] = $this->getDays()->map->translate()->all();
        $data['occasion_frequency'] = $this->occasionFrequency();
        $data['tags'] = $this->tags->map->translate()->all();
        $data['url'] = $this->url();

        return $data;
    }
}
