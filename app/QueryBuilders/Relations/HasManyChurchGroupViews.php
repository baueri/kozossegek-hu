<?php

namespace App\QueryBuilders\Relations;

use App\QueryBuilders\GroupViews;

trait HasManyChurchGroupViews
{
    public function groups()
    {
        $this->hasMany(GroupViews::class);
    }
}
