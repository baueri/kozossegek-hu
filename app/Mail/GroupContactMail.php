<?php

namespace App\Mail;

use Framework\Mail\Mailable;
use App\Models\Group;

class GroupContactMail extends Mailable
{

    public function __construct($request, Group $group)
    {
        $this->subject('kozossegek.hu - Új érdeklődő szeretné felvenni a kapcsolatot a közösséggel')
            ->view('mail.group-contact')
            ->with([
                'name' => strip_tags($request['name']),
                'email' => strip_tags($request['email']),
                'message' => str_replace(PHP_EOL, '<br>', strip_tags($request['message'])),
                'group' => $group
            ]);
    }
}
