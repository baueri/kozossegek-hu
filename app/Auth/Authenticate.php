<?php

namespace App\Auth;

use App\Models\User;
use App\QueryBuilders\Users;
use Framework\Support\Password;
use Framework\Traits\ManagesErrors;

class Authenticate
{
    use ManagesErrors;

    public function __construct(
        private Users $repository
    ) {
    }

    public function authenticate(?string $username, ?string $password): ?User
    {
        $user = $this->repository->byAuth($username)->first();

        if (!$user || ! Password::verify($password, $user->password)) {
            $this->pushError('Hibás felhasználónév vagy jelszó!');
            return null;
        }

        if (!$user->isActive()) {
            $this->pushError(<<<EOT
                A fiókod még nincs aktiválva.
                <a href='#' onclick='resendActivationEmail("{$user->email}"); return false;'><br/>
                <b>Aktivációs email újraküldése</b></a>
            EOT);
            return null;
        }

        return $user;
    }

    public function authenticateBySession()
    {
        $result = db()->first('select user_id from user_sessions where unique_id=?', [session_id()]);

        if ($result && $userId = $result['user_id']) {
            $user = $this->repository->find($userId);
            Auth::setUser($user);
        }
    }
}
