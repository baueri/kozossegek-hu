<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\User;
use Framework\Mail\Mailable;
use Framework\Support\Collection;

class GroupsInactivated extends Mailable
{
    protected ?string $view = 'email_templates:groups_inactivated';

    public function __construct(User $user, Collection $groups)
    {
        $this->with(['name' => $user->name, 'groups' => $groups->pluck('name')->toList()]);
    }
}
