<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserToken;
use Carbon\Carbon;
use Framework\Repository;

/**
 * @phpstan-extends \Framework\Repository<\App\Models\UserToken>
 */
class UserTokens extends Repository
{
    public function getByToken(string $token): ?UserToken
    {
        if (!$token) {
            return null;
        }
        return $this->getInstance($this->getBuilder()->where('token', $token)->first());
    }

    /**
     * @throws \Exception
     */
    public function createUserToken(User $user, string $page, ?Carbon $expireDate = null, string|array $data = null): UserToken
    {
        $instance = $this->make($user, $page, $expireDate, $data);

        $this->save($instance);

        return $instance;
    }

    /**
     * @throws \Exception
     */
    public function createActivationToken(User $user): UserToken
    {
        return $this->createUserToken($user, route('portal.user.activate'));
    }

    /**
     * @throws \Exception
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

    public static function getModelClass(): string
    {
        return UserToken::class;
    }

    public static function getTable(): string
    {
        return 'user_token';
    }
}
