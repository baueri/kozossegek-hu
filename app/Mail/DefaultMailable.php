<?php

namespace App\Mail;

use Framework\Mail\Mailable;

class DefaultMailable extends Mailable
{
    protected bool $useDefaultTemplate = true;
}
