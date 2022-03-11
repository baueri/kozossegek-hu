<?php

namespace App\Portal\Services;

use App\Admin\Group\Services\CreateGroup;
use App\Enums\Denomination;
use App\Exception\EmailTakenException;
use App\Mail\NewGroupEmail;
use App\Models\User;
use App\Repositories\UserTokens;
use Framework\Exception\FileTypeNotAllowedException;
use Framework\Mail\Mailer;
use Framework\Support\Collection;
use Framework\Traits\ManagesErrors;
use Legacy\Group;
use PHPMailer\PHPMailer\Exception;

class PortalCreateGroup
{
    use ManagesErrors;

    private CreateUser $createUser;

    private CreateGroup $createGroup;

    private UserTokens $userTokens;

    public function __construct(CreateUser $createUser, CreateGroup $createGroup, UserTokens $userTokens)
    {
        $this->createUser = $createUser;
        $this->createGroup = $createGroup;
        $this->userTokens = $userTokens;
    }

    /**
     * @throws Exception
     * @throws EmailTakenException|FileTypeNotAllowedException
     * @throws \Exception
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
            'description',
            'image',
            'join_mode'
        );

        $data['denomination'] = Denomination::katolikus->name;

        if (!$user) {
            $user = $this->createUser->create(collect([
                'name' => $requestData['user_name'],
                'email' => $requestData['email'],
                'password' => $requestData['password'],
                'phone_number' => $requestData['phone_number']
            ]));

            $userToken = $this->userTokens->createActivationToken($user);

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
            event_logger()->logEvent('group_created', ['group_id' => $group->id], $user);
            (new Mailer($user->email))->send($mailable);
            return $group;
        }

        $this->errors = $this->createGroup->getErrors();
        throw new Exception('Nem sikerült a csoport létrehozása');
    }
}
