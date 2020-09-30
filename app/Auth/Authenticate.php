<?php
namespace App\Auth;

use App\Repositories\UserRepository;
use Framework\Exception\UnauthorizedException;
use Framework\Support\Password;

class Authenticate
{

    /**
     *
     * @var UserRepository
     */
    private $repository;

    /**
     * UserAuth constructor.
     *
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     *
     * @param
     *            $username
     * @param
     *            $password
     * @throws UnauthorizedException
     */
    public function authenticate($username, $password)
    {
        $user = $this->repository->findByAuth($username);
        
        if (! $user || ! Password::verify($password, $user->password)) {
            throw new UnauthorizedException('invalid username or password');
        }
        
        return $user;
    }

    public function authenticateBySession()
    {
        $userId = db()->first('select user_id from user_sessions where unique_id=?', [
            session_id()
        ])['user_id'];
        
        if ($userId) {
            $user = $this->repository->find($userId);
            Auth::setUser($user);
        }
    }
}