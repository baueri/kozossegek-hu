<?php

namespace App\QueryBuilders;

use App\Models\OsmInstitute;
use Framework\Model\EntityQueryBuilder;

class OsmInstitutes extends EntityQueryBuilder
{
    protected static function getModelClass(): string
    {
        return OsmInstitute::class;
    }
}
