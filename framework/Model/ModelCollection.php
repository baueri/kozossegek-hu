<?php

namespace Framework\Model;

use Framework\Support\Collection;

/**
 * @template T of \Framework\Model\Entity
 */
class ModelCollection extends Collection
{
    public function getIds(): Collection
    {
        return $this->map(fn ($model) => $model->getId());
    }
}
