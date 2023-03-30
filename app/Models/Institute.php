<?php

namespace App\Models;

use App\Helpers\InstituteHelper;
use Framework\Model\Entity;
use Framework\Model\HasTimestamps;
use Framework\Support\StringHelper;

/**
 * @property string $name
 * @property string $name2
 * @property string $city
 * @property string $address
 * @property null|string $leader_name
 * @property string $created_at
 * @property null|string $updated_at
 * @property null|string $deleted_at
 * @property null|string $district
 * @property null|int $user_id
 * @property int $approved
 * @property null|int $miserend_id
 * @property null|string $image_url
 * @property null|string $website
 * @property string $lat
 * @property string $lon
 * @property null|User $user
 * @property string $slug
 */
class Institute extends Entity
{
    use HasTimestamps;

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
        [$varos, $intezmeny] = explode('/', $this->slug);

        $data = compact('varos', 'intezmeny', 'ref');
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
