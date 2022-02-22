<?php

namespace App\Models\Traits;

use App\Enums\AgeGroup;
use App\Enums\Denomination;
use App\Enums\WeekDay;
use App\Enums\GroupStatusEnum;
use App\Enums\JoinMode;
use App\Helpers\GroupHelper;
use App\Models\UserLegacy;
use Framework\File\File;
use Framework\Support\Collection;
use Framework\Support\StringHelper;

trait GroupTrait
{
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
        return lang('occasion_frequency.' . $this->occasion_frequency);
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

    public function isVisibleBy(?UserLegacy $user): bool
    {
        if ($user && ($user->isAdmin() || $this->user_id == $user->id)) {
            return true;
        }

        if ($this->pending == 0 && $this->status == GroupStatusEnum::ACTIVE) {
            return true;
        }

        return false;
    }

    public function getEditUrl(): string
    {
        return get_site_url() . route('portal.edit_group', $this);
    }

    public function isEditableBy(?UserLegacy $user): bool
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
}
