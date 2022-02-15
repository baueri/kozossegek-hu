<?php

namespace App\Repositories;

use App\Models\UserToken;
use App\Models\UserLegacy;
use DateTime;
use Framework\Repository;

/**
 * @phpstan-extends \Framework\Repository<\App\Models\UserToken>
 */
class UserTokens extends Repository
{
    public function getByToken(string $token)
    {
        return $this->getInstance($this->getBuilder()->where('token', $token)->first());
    }

    /**
     * @throws \Exception
     */
    public function createUserToken(UserLegacy $user, string $page): UserToken
    {
        $instance = $this->make($user, $page);

        $this->save($instance);

        return $instance;
    }

    /**
     * @throws \Exception
     */
    public function createActivationToken(UserLegacy $user): UserToken
    {
        return $this->createUserToken($user, route('portal.user.activate'));
    }

    /**
     * @throws \Exception
     */
    public function make(UserLegacy $user, string $page): UserToken
    {
        return $this->getInstance([
            'token' => bin2hex(random_bytes(20)),
            'email' => $user->email,
            'page' => $page,
            'expires_at' => (new DateTime())->modify('+1 day')->format('Y-m-d H:i:s')
        ]);
    }

    public static function getModelClass(): string
    {
        return UserToken::class;
    }

    public static function getTable(): string
    {
        return 'user_token';
    }
}
