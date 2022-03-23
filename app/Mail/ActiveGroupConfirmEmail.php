<?php

namespace App\Mail;

use App\Models\ChurchGroup;
use App\Models\User;
use Framework\Mail\Mailable;

class ActiveGroupConfirmEmail extends Mailable
{
    public function __construct(
        private readonly ChurchGroup $group,
        private readonly User $maintainer
    ) {
        $this->with(['group_name' => $this->group->name, 'maintainer_name' => $this->maintainer->name])
            ->view('email_templates:confirm_active_group')
            ->subject(site_name() . ' - Közösség aktív státuszának megerősítése');
    }
}
