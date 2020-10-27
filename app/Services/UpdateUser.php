<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Users;
use Framework\Http\Message;
use Framework\Support\Password;

class UpdateUser
{
   /**
    * @var Users
    */
    private $users;

   /**
    * @param Users $users
    */
    public function __construct(Users $users)
    {
        $this->users = $users;
    }

    public function update(User $user, $changes, array $passwordChange = null)
    {
        $emailTaken = builder('users')->where('id', '<>', $user->id)->where('email', $changes['email'])->exists();

        if ($emailTaken) {
            Message::danger('Ez az email cím már foglalt!');
            return false;
        }

        if (array_filter($passwordChange)) {

            $password1 = $passwordChange['new_password'];
            $password2 = $passwordChange['new_password_again'];

            if (!Password::verify($passwordChange['old_password'], $user->password)) {
                Message::danger('Hibás régi jelszó!');
                return false;
            }

            if (!$password1 || !$password2 || $password1 !== $password2) {
                Message::danger('A két jelszó nem egyezik!');
                return false;
            }

            $user->password = Password::hash($password1);
        }

        $user->update($changes);

        return $this->users->save($user);
    }
}
