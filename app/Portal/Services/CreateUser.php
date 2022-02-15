<?php

namespace App\Portal\Services;

use App\QueryBuilders\UserLegalNotices;
use App\Exception\EmailTakenException;
use App\Models\User;
use App\Repositories\Users;
use Framework\Support\Collection;
use Framework\Support\Password;

class CreateUser
{
    private Users $users;

    private UserLegalNotices $legalNotices;

    public function __construct(Users $users, UserLegalNotices $legalNotices)
    {
        $this->users = $users;
        $this->legalNotices = $legalNotices;
    }

    /**
     * @param Collection $data
     * @return User
     * @throws EmailTakenException
     */
    public function create(Collection $data): User
    {
        if ($this->users->getUserByEmail($data['email'])) {
            throw new EmailTakenException();
        }

        $data = $data->only('name', 'email', 'password', 'phone_number');
        $data['password'] = Password::hash($data['password']);

        $user = $this->users->create($data);

        $this->legalNotices->updateOrInsertCurrentFor($user);

        return $user;
    }
}
