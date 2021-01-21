<?php

namespace App\Mailable;

use Framework\Mail\Mailable;

class CriticalErrorEmail extends Mailable
{
    protected ?string $view = 'email_templates:critical_error';

    private $exception;

    public function __construct($exception)
    {
        $this->exception = $exception;
    }

    public function build()
    {
        $this->with(['exception' => $this->exception])
            ->subject('kozossegek.hu HIBA: ' . $this->exception->getMessage());
    }
}
