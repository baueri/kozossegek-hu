<?php


namespace Framework\Http\Auth\Authenticators;


use App\Repositories\UserRepository;
use Framework\Support\Password;

class UserAuthenticator implements Authenticator
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UserAuthenticator constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @inheritDoc
     */
    public function authenticate($credentials)
    {
        $userId = builder()
            ->select('user_id')
            ->from('user_sessions')
            ->where('session_id', session_id())
            ->first()['user_id'];

        if ($userId) {
            return $this->userRepository->find($userId);
        }
    }
}