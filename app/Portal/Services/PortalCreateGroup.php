<?php

declare(strict_types=1);

namespace App\Portal\Services;

use App\Admin\Group\Services\BaseGroupService;
use App\Admin\Group\Services\CreateGroup;
use App\Enums\Denomination;
use App\Enums\GroupStatus;
use App\Enums\GroupPending;
use App\Exception\EmailTakenException;
use App\Mail\NewGroupEmail;
use App\Models\ChurchGroup;
use App\Models\User;
use App\QueryBuilders\UserTokens;
use Framework\Exception\FileTypeNotAllowedException;
use Framework\Mail\Mailer;
use Framework\Support\Collection;
use Framework\Traits\ManagesErrors;
use PHPMailer\PHPMailer\Exception;

class PortalCreateGroup
{
    use ManagesErrors;

    public function __construct(
        private readonly CreateUser $createUser,
        private readonly CreateGroup $createGroup,
        private readonly UserTokens $userTokens
    ) {
    }

    /**
     * @throws Exception
     * @throws EmailTakenException|FileTypeNotAllowedException
     * @throws \Exception
     */
    public function createGroup(Collection $requestData, ?array $fileData, ?User $user): ?ChurchGroup
    {
        $data = $requestData->sanitize()->only(
            'name',
            'institute_id',
            'institute',
            'age_group',
            'occasion_frequency',
            'on_days',
            'spiritual_movement',
            'tags',
            'group_leaders',
            'join_mode'
        );
        $data['description'] = strip_tags($requestData->get('description'), BaseGroupService::ALLOWED_TAGS);
        $data['image'] = $requestData->get('image');

        // Ha nincs még belépve, akkor létrehozunk egy új fiókot a megadott adatokkal
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
        $data['pending'] = GroupPending::pending;
        $data['denomination'] = Denomination::katolikus;
        $data['status'] = GroupStatus::active;

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
