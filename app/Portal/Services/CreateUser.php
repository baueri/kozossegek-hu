<?php

namespace App\Portal\Services;

use App\Exception\EmailTakenException;
use App\Mail\RegistrationEmail;
use App\Models\User;
use App\Repositories\Users;
use App\Repositories\UserTokens;
use Framework\Mail\Mailer;
use Framework\Support\Collection;
use Framework\Support\Password;
use Framework\Support\StringHelper;

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

        $data = $data->only('name', 'email', 'password');
        $data['password'] = Password::hash($data['password']);

        /* @var $user User */
        return $this->users->create($data);
    }
}
