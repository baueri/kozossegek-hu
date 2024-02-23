<?php

namespace App\Portal\Services;

use App\Exception\EmailTakenException;
use App\Models\User;
use App\QueryBuilders\UserLegalNotices;
use App\QueryBuilders\Users;
use Exception;
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
     * @throws Exception
     */
    public function create(Collection $data): User
    {
        $name = strip_tags($data->get('name'));
        $email = filter_var($data->get('email'), FILTER_VALIDATE_EMAIL);
        $password = $data->get('password');
        $phone_number = strip_tags((string) $data->get('phone_number'));

        if (!$email) {
            // @todo user-barát hibaüzenet
            throw new Exception('invalid email');
        }

        if ($this->users->byEmail($email)->exists()) {
            throw new EmailTakenException();
        }

        $user = $this->users->create([
            'name' => $name,
            'email' => $email,
            'password' => Password::hash($password),
            'phone_number' => $phone_number
        ]);

        $this->legalNotices->updateOrInsertCurrentFor($user);

        log_event('create_user');

        return $user;
    }
}
