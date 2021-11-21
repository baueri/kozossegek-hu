<?php

namespace App\Mailable;

use App\Auth\Auth;
use Framework\Mail\Mailable;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

class ThrowableCriticalErrorEmail extends Mailable
{
    protected ?string $view = 'email_templates:critical_error';

    /**
     * @param \Throwable $exception
     */
    public function __construct(\Throwable $exception)
    {
        $decector = new CrawlerDetect();
        $this->with([
            'exception' => $exception,
            'request' => request(),
            'user' => Auth::user(),
            'referer' => $_SERVER['HTTP_REFERER'] ?? null,
            'is_bot' => $decector->isCrawler()
        ])
        ->subject(get_site_url() . ' HIBA: ' . $exception->getMessage());
    }
}
