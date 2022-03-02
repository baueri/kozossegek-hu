<?php

namespace App\Models\Traits;

use App\Helpers\InstituteHelper;

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
}
