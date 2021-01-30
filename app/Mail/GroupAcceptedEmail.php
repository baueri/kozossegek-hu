<?php

namespace App\Mail;

use App\Models\GroupView;
use Framework\Mail\Mailable;

class GroupAcceptedEmail extends Mailable
{
    public function __construct(GroupView $group)
    {
        $this->with([
            'name' => $group->group_leaders,
            'group_name' => $group->name,
            'page_link' => $group->url(),
        ])->view('mail.group_accepted')
        ->subject('kozossegek.hu - Közösség jóváhagyva');
    }
}
