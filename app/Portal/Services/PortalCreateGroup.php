<?php

namespace App\Portal\Services;

use App\Admin\Group\Services\CreateGroup;
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
     */
    public function createGroup(Collection $requestData, ?array $fileData, ?User $user): ?Group
    {
        $data = $requestData->only(
            'status',
            'name',
            'denomination',
            'institute_id',
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

        $mailable = new NewGroupEmail();

        if (!$user) {
            $user = $this->createUser->create(collect([
                'name' => $requestData['user_name'],
                'email' => $requestData['email'],
                'password' => $requestData['password']
            ]));

            $userToken = $this->userTokens->createUserToken($user, route('portal.user.activate'));
            $mailable->with(['token' => $userToken]);
            $mailable->view('mail.created_group_email.created_group_with_new_user');
            $mailable->subject('kozossegek.hu - Regisztráció');
        }

        $data['user_id'] = $user->id;

        $group = $this->createGroup->create(collect($data), $fileData);

        if ($group) {
            $mailable->with(['group' => $group]);
            Mailer::make()->to($user->email)->send($mailable);

            return $group;
        }

        $this->errors = $this->createGroup->getErrors();

        return null;
    }
}
