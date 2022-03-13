<?php

namespace App\QueryBuilders;

use App\Models\OsmMarker;
use Framework\Model\EntityQueryBuilder;

class OsmMarkers extends EntityQueryBuilder
{
    protected static function getModelClass(): string
    {
        return OsmMarker::class;
    }
}
