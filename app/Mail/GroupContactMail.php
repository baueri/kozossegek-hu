<?php

namespace App\Mail;

use Framework\Mail\Mailable;
use App\Models\Group;

class GroupContactMail extends Mailable
{

    public function __construct($data, Group $group)
    {
        $this->subject('kozossegek.hu - Új érdeklődő szeretné felvenni a kapcsolatot a közösséggel')
            ->view('mail.group-contact')
            ->with([
                'name' => strip_tags($data['name']),
                'email' => strip_tags($data['email']),
                'message' => str_replace(PHP_EOL, '<br>', strip_tags($data['message'])),
                'group' => $group
            ]);
    }
}
