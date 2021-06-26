<?php

namespace App\Portal\Services;

use App\EntityQueryBuilders\UserLegalNotices;
use App\Exception\EmailTakenException;
use App\Mail\RegistrationEmail;
use App\Models\User;
use App\Repositories\Users;
use Framework\Support\Collection;
use Framework\Support\Password;

class CreateUser
{
    private Users $users;

    public function __construct(Users $users)
    {
        $this->users = $users;
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

        /* @var $user User */
        $user = $this->users->create($data);

        UserLegalNotices::init()->updateOrInsertCurrentFor($user);

        return $user;
    }
}
