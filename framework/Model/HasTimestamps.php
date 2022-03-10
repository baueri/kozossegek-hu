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
    public function createdAt(): Carbon
    {
        return Carbon::parse($this->created_at);
    }

    public function isDeleted(): bool
    {
        return (bool) $this->deleted_at;
    }
}