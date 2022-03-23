<?php

namespace App\Mail;

use Framework\Mail\Mailable;

class GroupContactMail extends Mailable
{
    public function __construct(string $name, string $email, string $message)
    {
        $this->subject(site_name() . ' - Új érdeklődő szeretné felvenni a kapcsolatot a közösséggel')
            ->replyTo($email)
            ->view('email_templates:group-contact')
            ->with([
                'name' => $name,
                'email' => $email,
                'message' => str_replace(PHP_EOL, '<br>', $message),
            ]);
    }
}
