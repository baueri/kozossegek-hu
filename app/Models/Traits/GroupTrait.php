<?php

namespace App\Models\Traits;

use App\Enums\AgeGroup;
use App\Enums\Denomination;
use App\Enums\OccasionFrequency;
use App\Enums\WeekDay;
use App\Enums\GroupStatus;
use App\Enums\JoinMode;
use App\Helpers\GroupHelper;
use App\Models\User;
use App\Services\SystemAdministration\SiteMap\EntitySiteMappable;
use Framework\File\File;
use Framework\Model\HasTimestamps;
use Framework\Support\Collection;
use Framework\Support\StringHelper;

trait GroupTrait
{
    use EntitySiteMappable;
    use HasTimestamps;

    private ?string $cachedUrl = null;

    public function ageGroup(): string
    {
        if ($this->getAgeGroups()->count() > 1) {
            return 'vegyes';
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


    public function joinMode(): string
    {
        return JoinMode::getText($this->join_mode) ?? '';
    }

    /**
     * @todo képmentést megoldani!!!
     * @return boolean [description]
     */
    public function hasImage(): bool
    {
        return false;
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

        return $user->isAdmin() || $user->id == $this->user_id;
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

    public function pendingStatusIs(int $status): bool
    {
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
}
