<?php

declare(strict_types=1);

namespace App\Portal\Controllers;

use App\Enums\SpiritualMovementType;

class MonasticCommunityController extends SpiritualMovementController
{
    protected SpiritualMovementType $type = SpiritualMovementType::monastic_community;

    protected string $title = 'Szerzetesrendek';

    protected string $description = '';
}