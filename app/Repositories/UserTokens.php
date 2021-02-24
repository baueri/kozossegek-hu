<?php

namespace App\Repositories;

use App\Models\UserToken;
use App\Models\User;
use Framework\Repository;

class UserTokens extends Repository
{
    /**
     * @param $token
     * @return UserToken
     */
    public function getByToken($token)
    {
        return $this->getInstance($this->getBuilder()->where('token', $token)->first());
    }

    /**
     * @param User $user
     * @param string $page
     * @return UserToken
     * @throws \Exception
     */
    public function createUserToken(User $user, string $page)
    {
        $instance = $this->make($user, $page);

        $this->save($instance);

        return $instance;
    }

    public function createActivationToken(User $user)
    {
        return $this->createUserToken($user, route('portal.user.activate'));
    }

    /**
     * @param User $user
     * @param string $page
     * @return UserToken
     * @throws \Exception
     */
    public function make(User $user, string $page)
    {
        return $this->getInstance([
            'token' => bin2hex(random_bytes(20)),
            'email' => $user->email,
            'page' => $page,
            'expires_at' => (new \DateTime())->modify('+1 day')->format('Y-m-d H:i:s')
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
