<?php

namespace App\Mail;

use Framework\Mail\Mailable;

class NewGroupEmail extends Mailable
{
    protected string $view = 'mail.created_group_email.created_group';

    public string $subject = 'kozossegek.hu - Új csoport';
}
