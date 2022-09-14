<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Models\User;
use DateTime;
use Exception;
use Framework\Model\EntityQueryBuilder;
use App\Models\UserToken;

/**
 * @phpstan-extends EntityQueryBuilder<\App\Models\UserToken>
 */
class UserTokens extends EntityQueryBuilder
{
    public const TABLE = 'user_token';

    public function getByToken(string $token)
    {
        return $this->query()->where('token', $token)->first();
    }

    /**
     * @throws Exception
     */
    public function createUserToken(User $user, string $page): UserToken
    {
        $instance = $this->make($user, $page);

        $this->create($instance);

        return $instance;
    }

    /**
     * @throws Exception
     */
    public function createActivationToken(User $user): UserToken
    {
        return $this->createUserToken($user, route('portal.user.activate'));
    }

    /**
     * @throws Exception
     */
    public function make(User $user, string $page): UserToken
    {
        return $this->getInstance([
            'token' => bin2hex(random_bytes(20)),
            'email' => $user->email,
            'page' => $page,
            'expires_at' => (new DateTime())->modify('+1 day')->format('Y-m-d H:i:s')
        ]);
    }
}
