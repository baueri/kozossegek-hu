<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Models\ThirdPartyCredential;
use App\Models\User;
use Framework\Model\EntityQueryBuilder;
use Framework\Model\Relation\Has;
use Framework\Model\Relation\Relation;
use Framework\PasswordGenerator;
use InvalidArgumentException;

/**
 * @phpstan-extends EntityQueryBuilder<ThirdPartyCredential>
 */
class ThirdPartyCredentials extends EntityQueryBuilder
{
    public function getCredentials(User $user, string $appName): ThirdPartyCredential
    {
        if (!$appName) {
            throw new InvalidArgumentException('app name must not be empty');
        }

        $appSecret = (new PasswordGenerator())->generate(40);

        return $this->updateOrCreate(['app_name' => $appName, 'user_id' => $user->getId()], ['api_key' => $appSecret]);
    }
    
    public function byCredentials(string $appName, string $secret): self
    {
        return $this->where('app_name', $appName)
            ->where('api_key', $secret);
    }

    public function user(): Relation
    {
        return $this->has(Has::one, Users::class, 'id', 'user_id');
    }
}
