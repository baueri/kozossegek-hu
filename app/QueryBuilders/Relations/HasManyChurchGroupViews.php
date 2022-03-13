<?php

namespace App\QueryBuilders\Relations;

use App\QueryBuilders\GroupViews;
use Framework\Model\Relation\Has;
use Framework\Model\Relation\Relation;

trait HasManyChurchGroupViews
{
    public function groups(): Relation
    {
        return $this->has(Has::many, GroupViews::class);
    }
}
