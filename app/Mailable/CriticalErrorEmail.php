<?php

namespace App\Mailable;

use App\Auth\Auth;
use Framework\Mail\Mailable;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

class CriticalErrorEmail extends Mailable
{
    protected ?string $view = 'email_templates:critical_error';

    private $exception;

    /**
     * CriticalErrorEmail constructor.
     * @param \Throwable $exception
     */
    public function __construct($exception)
    {
        $this->exception = $exception;
    }

    public function build()
    {
        $decector = new CrawlerDetect();
        $this->with([
            'exception' => $this->exception,
            'request' => request(),
            'user' => Auth::user(),
            'referer' => $_SERVER['HTTP_REFERER'] ?? null,
            'is_bot' => $decector->isCrawler()
        ])
        ->subject(get_site_url() . ' HIBA: ' . $this->exception->getMessage());
    }
}
