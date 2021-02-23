<?php

namespace App\Mailable;

use Framework\Http\Request;
use Framework\Mail\Mailable;

class CriticalErrorEmail extends Mailable
{
    protected ?string $view = 'email_templates:critical_error';

    private $exception;

    private Request $request;

    /**
     * CriticalErrorEmail constructor.
     * @param \Throwable $exception
     */
    public function __construct($exception)
    {
        $this->request = request();
        $this->exception = $exception;
    }

    public function build()
    {
        $this->with(['exception' => $this->exception, 'request' => $this->request])
            ->subject('kozossegek.hu HIBA: ' . $this->exception->getMessage());
    }
}
