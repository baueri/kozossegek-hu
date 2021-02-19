<?php

namespace App\Services;

use App\Mail\RegistrationByGroupEmailForFirstUsers;
use App\Mail\RegistrationEmail;
use App\Models\Group;
use App\Models\GroupView;
use App\Models\User;
use App\Models\UserToken;
use App\Repositories\Groups;
use App\Repositories\GroupViews;
use App\Repositories\Users;
use App\Repositories\UserTokens;
use Exception;
use Framework\Mail\Mailable;
use Framework\Mail\Mailer;
use Framework\PasswordGenerator;
use Framework\Support\Password;
use InvalidArgumentException;

/**
 * Description of CreateUserFromGroup
 *
 * @author ivan
 */
class CreateUserFromGroup
{

    /**
     * @var Groups
     */
    private Groups $groups;

    /**
     * @var UserTokens
     */
    private UserTokens $userTokenRepository;

    /**
     * @var Users
     */
    private Users $userRepository;

    /**
     *
     * @param Users $userRepository
     * @param UserTokens $userTokenRepository
     * @param Groups $groups
     */
    public function __construct(Users $userRepository, UserTokens $userTokenRepository, Groups $groups)
    {
        $this->userRepository = $userRepository;
        $this->userTokenRepository = $userTokenRepository;
        $this->groups = $groups;
    }

    public function createUserAndAddToGroup(Group $group, string $emailTemplate = 'register')
    {
        $this->validate($group);

        $user = $this->userRepository->getUserByEmail($group->group_leader_email);

        if (!$user) {
            $password = (new PasswordGenerator(6))
                ->useLower(false)
                ->generate();
            $userData = [
                'name' => static::getUserName($group),
                'email' => $group->group_leader_email,
                'password' => Password::hash($password)
            ];

            $user = $this->userRepository->create($userData);

            $token = $this->userTokenRepository->createUserToken($user, route('portal.user.activate'));

            $groupView = app()->make(GroupViews::class)->find($group->id);

            $mailable = $this->getMailable($emailTemplate, $user, $token, $groupView, $password);

            Mailer::make()->to($user->email)->send($mailable);
        }

        $group->user_id = $user->id;

        $this->groups->save($group);

        return $user;
    }

    /**
     * @param string $template
     * @param User $user
     * @param UserToken $token
     * @param GroupView $group
     * @param string $password
     * @return Mailable
     */
    private function getMailable(string $template, User $user, UserToken $token, GroupView $group, string $password)
    {
        if ($template == 'register_by_group') {
            return new RegistrationByGroupEmailForFirstUsers($user, $password, $token, $group);
        }

        if ($template == 'register') {
            return RegistrationEmail::make($user, $token);
        }

        throw new InvalidArgumentException('Hibás email sablon!');
    }

    private function validate(Group $group)
    {

        if ($group->user_id) {
            throw new Exception('Már van felhasználói fiók csatolva ehhez a csoporthoz!');
        }

        if (!$group->group_leader_email) {
            throw new Exception('Nincs kapcsolattartó email megadva!');
        }

        if (!$group->group_leaders) {
            throw new Exception('Nincs kapcsolattartó felhasználónév megadva');
        }
    }

    /**
     *
     * @param Group $group
     * @return string
     */
    private static function getUserName(Group $group): string
    {
        if (($commaPos = strpos($group->group_leaders, ',')) !== false) {
            return substr($group->group_leaders, 0, $commaPos);
        }

        return $group->group_leaders;
    }
}
