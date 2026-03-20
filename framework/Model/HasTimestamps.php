<?php

namespace Framework\Model;

use Carbon\Carbon;

/**
 * @property string $created_at
 * @property null|string $updated_at
 * @property null|string $deleted_at
 */
trait HasTimestamps
{
    public static function updatedCol(): ?string
    {
        return 'updated_at';
    }

    public function createdAt(): Carbon
    {
        return Carbon::parse($this->created_at);
    }

    public function deletedAt(): Carbon
    {
        return Carbon::parse($this->deleted_at);
    }

    public function updatedAt(): Carbon
    {
        return Carbon::parse($this->updated_at);
    }

    public function isDeleted(): bool
    {
        return (bool) $this->deleted_at;
    }
}
