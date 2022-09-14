<?php

namespace App\QueryBuilders;

use App\Models\OsmMarker;
use Framework\Model\EntityQueryBuilder;

class OsmMarkers extends EntityQueryBuilder
{
    public static function getModelClass(): string
    {
        return OsmMarker::class;
    }
}
