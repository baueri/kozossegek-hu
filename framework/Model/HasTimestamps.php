<?php

namespace Framework\Model;

use Carbon\Carbon;

/**
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
trait HasTimestamps
{
    public function createdAt(): Carbon
    {
        return Carbon::parse($this->created_at);
    }
}