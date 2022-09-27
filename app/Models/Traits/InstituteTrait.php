<?php

namespace App\Models\Traits;

use App\Helpers\InstituteHelper;
use Framework\Support\StringHelper;

trait InstituteTrait
{
    public function getImageRelPath(): string
    {
        return $this->image_url ?? InstituteHelper::getImageRelPath($this->id);
    }

    public function getImageStoragePath(): string
    {
        return InstituteHelper::getImageStoragePath($this->id);
    }

    public function hasImage(): bool
    {
        return !is_null($this->image_url) || file_exists($this->getImageStoragePath());
    }

    public function groupsUrl(?string $ref = null): string
    {
        $data = ['varos' => StringHelper::slugify($this->city), 'intezmeny' => StringHelper::slugify("{$this->name}-{$this->id}"), 'ref' => $ref];
        return route('portal.institute_groups', array_filter($data));
    }

    public function getMiserendUrl(): ?string
    {
        if (!$this->miserend_id) {
            return null;
        }

        return "https://miserend.hu/templom/{$this->miserend_id}";
    }

    public function latlon(): string
    {
        return "{$this->lat},{$this->lon}";
    }
}
