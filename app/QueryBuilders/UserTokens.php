<?php

declare(strict_types=1);

namespace App\QueryBuilders;

use App\Models\User;
use App\Models\UserToken;
use Carbon\Carbon;
use Exception;
use Framework\Model\EntityQueryBuilder;

/**
 * @phpstan-extends EntityQueryBuilder<UserToken>
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
    public function createUserToken(User $user, string $page, ?Carbon $expireDate = null, string|array $data = null): UserToken
    {
        $instance = $this->make($user, $page, $expireDate, $data);

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
    public function make(User $user, string $page, ?Carbon $exireDate = null, string|array $data = null): UserToken
    {
        return $this->getInstance([
            'token' => bin2hex(random_bytes(20)),
            'email' => $user->email,
            'page' => $page,
            'expires_at' => $exireDate ? $exireDate->toDateTimeString() : now()->modify('+1 day')->toDateTimeString(),
            'data' => is_array($data) ? json_encode($data) : $data
        ]);
    }
}
