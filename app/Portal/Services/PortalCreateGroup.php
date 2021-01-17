<?php

namespace App\Portal\Services;

use App\Admin\Group\Services\CreateGroup;
use App\Enums\DenominationEnum;
use App\Exception\EmailTakenException;
use App\Mail\NewGroupEmail;
use App\Models\Group;
use App\Models\User;
use App\Repositories\UserTokens;
use Framework\Mail\Mailer;
use Framework\Support\Collection;
use Framework\Traits\ManagesErrors;
use PHPMailer\PHPMailer\Exception;

class PortalCreateGroup
{
    use ManagesErrors;

    /**
     * @var CreateUser
     */
    private CreateUser $createUser;

    /**
     * @var CreateGroup
     */
    private CreateGroup $createGroup;

    /**
     * @var UserTokens
     */
    private UserTokens $userTokens;

    /**
     * PortalCreateGroup constructor.
     * @param CreateUser $createUser
     * @param CreateGroup $createGroup
     * @param UserTokens $userTokens
     */
    public function __construct(CreateUser $createUser, CreateGroup $createGroup, UserTokens $userTokens)
    {
        $this->createUser = $createUser;
        $this->createGroup = $createGroup;
        $this->userTokens = $userTokens;
    }

    /**
     * @param Collection $requestData
     * @param array|null $fileData
     * @param User|null $user
     * @return Group|null
     * @throws Exception
     * @throws EmailTakenException
     */
    public function createGroup(Collection $requestData, ?array $fileData, ?User $user): ?Group
    {
        $data = $requestData->only(
            'status',
            'name',
            'institute_id',
            'institute',
            'age_group',
            'occasion_frequency',
            'on_days',
            'spiritual_movement',
            'tags',
            'group_leaders',
            'group_leader_phone',
            'group_leader_email',
            'description',
            'image'
        );

        $data['denomination'] = DenominationEnum::KATOLIKUS;

        if (!$user) {
            $user = $this->createUser->create(collect([
                'name' => $requestData['user_name'],
                'email' => $requestData['email'],
                'password' => $requestData['password']
            ]));

            $userToken = $this->userTokens->createUserToken($user, route('portal.user.activate'));

            $mailable = new NewGroupEmail($user);
            $mailable->withNewUserMessage($userToken);
            $mailable->subject('kozossegek.hu - Regisztráció');
        } else {
            $mailable = new NewGroupEmail($user);
        }

        $mailable->with(['user_name' => $user->name]);

        $data['user_id'] = $user->id;

        $group = $this->createGroup->create(collect($data), $fileData);

        if ($group) {
            return $group;
        }

        $this->errors = $this->createGroup->getErrors();

        throw new Exception('Nem sikerült a csoport létrehozása');
    }
}
