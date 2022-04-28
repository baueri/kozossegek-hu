<?php

namespace App\Models;

use App\Auth\AuthUser;
use App\Models\Traits\UserTrait;
use App\QueryBuilders\Users;
use Framework\Model\Entity;
use Framework\Model\HasTimestamps;
use Framework\Model\ModelCollection;
use App\QueryBuilders\ChurchGroups;

/**
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $last_login
 * @property string $user_group
 * @property string $activated_at
 * @property string $phone_number
 * @property ModelCollection<ChurchGroup> $groups
 * @property int $groups_count
 * @method ChurchGroups groups()
 */
class User extends Entity implements AuthUser
{
    use UserTrait;
    use HasTimestamps;

    protected ?string $builder = Users::class;
}
