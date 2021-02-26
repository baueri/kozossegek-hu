<?php

namespace App\Mail;

use Framework\Mail\Mailable;

class GroupContactMail extends Mailable
{

    public function __construct($data)
    {
        $this->subject('kozossegek.hu - Új érdeklődő szeretné felvenni a kapcsolatot a közösséggel')
            ->view('email_templates:group-contact')
            ->with([
                'name' => strip_tags($data['name']),
                'email' => strip_tags($data['email']),
                'message' => str_replace(PHP_EOL, '<br>', strip_tags($data['message'])),
            ]);
    }
}
