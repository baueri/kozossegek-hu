<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Models\ThirdPartyCredential;
use Framework\Model\EntityQueryBuilder;

/**
 * @phpstan-extends EntityQueryBuilder<ThirdPartyCredential>
 */
class ThirdPartyCredentials extends EntityQueryBuilder
{

}
