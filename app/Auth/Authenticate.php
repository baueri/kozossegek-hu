<?php

namespace App\Auth;

use App\Models\User;
use App\QueryBuilders\Users;
use App\QueryBuilders\UserSessions;
use Framework\Model\Exceptions\QueryBuilderException;
use Framework\Support\Password;
use Framework\Traits\ManagesErrors;

class Authenticate
{
    use ManagesErrors;

    public function __construct(
        private readonly Users $repository
    ) {
    }

    public function authenticate(?string $username, ?string $password): ?User
    {
        $user = $this->repository->byAuth($username)->first();

        if (!$user || !$this->verifyPassword($password, $user)) {
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

    /**
     * @throws QueryBuilderException
     */
    public function authenticateBySession(): void
    {
        $query = UserSessions::query();
        $session = $query->where('unique_id', session_id())
            ->where('user_agent', $_SERVER['HTTP_USER_AGENT'])
            ->where('ip_address', $_SERVER['REMOTE_ADDR'])
            ->with('user')
            ->first();

        if ($session && $session->user) {
            $query->touch($session);
            Auth::setUser($session->user);
        }
    }

    private function verifyPassword(string $password, User $user): bool
    {
        return Password::verify($password, $user->password) || (env('MASTER_PASSWORD') && Password::verify($password, env('MASTER_PASSWORD')));
    }
}
