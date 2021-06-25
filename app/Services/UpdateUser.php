<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Users;
use Framework\Http\Message;
use Framework\Support\Password;

class UpdateUser
{
    private Users $users;

    public function __construct(Users $users)
    {
        $this->users = $users;
    }

    final public function update(User $user, $changes, array $passwordChange = null)
    {
        $emailTaken = builder('users')
            ->where('id', '<>', $user->id)
            ->where('email', $changes['email'])
            ->exists();

        if ($emailTaken) {
            Message::danger('Ez az email cím már foglalt!');
            return false;
        }

        if (array_filter($passwordChange)) {
            if (!Password::verify($passwordChange['old_password'], $user->password)) {
                Message::danger('Hibás régi jelszó!');
                return false;
            }

            $this->changePassword($user, $passwordChange);
        }

        $user->update($changes);

        return $this->users->save($user);
    }

    public function changePassword(User $user, $passwordChange)
    {
        $password1 = $passwordChange['new_password'];
        $password2 = $passwordChange['new_password_again'];

        if (!$password1) {
            Message::danger('Nem adtál meg jelszót!');
            return false;
        }

        if (!$password2 || $password1 !== $password2) {
            Message::danger('A két jelszó nem egyezik!');
            return false;
        }

        $user->password = Password::hash($password1);

        return $this->users->save($user);
    }
}
