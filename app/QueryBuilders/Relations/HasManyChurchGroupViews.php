<?php

namespace App\QueryBuilders\Relations;

use App\QueryBuilders\ChurchGroupViews;
use Framework\Model\Relation\Has;
use Framework\Model\Relation\Relation;

trait HasManyChurchGroupViews
{
    public function groups(): Relation
    {
        return $this->has(Has::many, ChurchGroupViews::class);
    }
}
