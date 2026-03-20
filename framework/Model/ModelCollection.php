<?php

declare(strict_types=1);

namespace Framework\Model;

use Framework\Support\Collection;

/**
 * @phpstan-template T of Entity
 * @phpstan-extends Collection<T>
 */
class ModelCollection extends Collection
{
    public function getIds(): Collection
    {
        return $this->map(fn ($model) => $model->getId());
    }
}
