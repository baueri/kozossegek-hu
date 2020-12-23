<?php

namespace App\Mail;

use Framework\Mail\Mailable;

class NewGroupEmail extends Mailable
{
    protected $view = 'mail.created_group_email.created_group';

    public $subject = 'kozossegek.hu - Új csoport';
}
