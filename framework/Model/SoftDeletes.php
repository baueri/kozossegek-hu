<?php

namespace Framework\Model;

/**
 * @mixin EntityQueryBuilder
 */
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

    public function softDelete(?Entity $entity = null): int
    {
        if ($entity) {
            return $this->query()->where('id', $entity->getId())->update(['deleted_at' => now()]);
        }
        return $this->update(['deleted_at' => now()]);
    }
}
