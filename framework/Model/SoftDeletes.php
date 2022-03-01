<?php

namespace Framework\Model;

trait SoftDeletes
{
    public function notDeleted(): static
    {
        return $this->apply('notDeleted');
    }

    public function trashed(): static
    {
        return $this->whereNotNull('deleted_at');
    }
}
