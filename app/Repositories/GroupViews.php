<?php

namespace App\Repositories;

use Framework\Repository;
use App\Models\GroupView;

class GroupViews extends Repository
{
    public static function getModelClass(): string
    {
        return GroupView::class;
    }

    public static function getTable(): string
    {
        return 'v_groups';
    }
}
