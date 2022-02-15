<?php

namespace App\Models\Traits;

use App\Enums\GroupStatusEnum;
use App\Enums\JoinMode;
use App\Helpers\GroupHelper;
use App\Models\User;
use Framework\File\File;
use Framework\Support\Collection;
use Framework\Support\StringHelper;

trait GroupTrait
{
    public function ageGroup(): string
    {
        return GroupHelper::parseAgeGroup($this->age_group);
    }

    public function getAgeGroups(): Collection
    {
        return GroupHelper::getAgeGroups($this->age_group);
    }

    public function denomination(): string
    {
        return lang("denomination.{$this->denomination}");
    }

    public function getDays(): Collection
    {
        $days = array_filter(explode(',', $this->on_days));
        $daysTranslated = [];

        foreach ($days as $day) {
            $daysTranslated[$day] = lang("day.$day");
        }

        return collect($daysTranslated);
    }

    public function occasionFrequency(): string
    {
        return lang('occasion_frequency.' . $this->occasion_frequency);
    }

    public function excerpt($words = 25): string
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

        if ($this->pending == 0 && $this->status == GroupStatusEnum::ACTIVE) {
            return true;
        }

        return false;
    }

    public function getEditUrl(): string
    {
        return get_site_url() . route('portal.edit_group', $this);
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
}
