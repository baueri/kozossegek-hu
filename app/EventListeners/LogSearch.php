<?php

namespace App\EventListeners;

use App\Services\EventLogger;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

class LogSearch extends BaseLogger
{
    public function __construct(EventLogger $eventLogRepo, private readonly CrawlerDetect $crawlerDetect)
    {
        parent::__construct($eventLogRepo);
    }

    public function trigger($event)
    {
        if (isset($event->data['status'])) {
            unset($event->data['status']);
        }

        if (isset($event->data['pending'])) {
            unset($event->data['pending']);
        }

        if ($this->shouldLog($event->data)) {
            $event->data['referer'] = $_SERVER['HTTP_REFERER'] ?? '';
            parent::trigger($event);
        }
    }

    private function shouldLog($filterData): bool
    {
        return !$this->crawlerDetect->isCrawler() &&
            array_filter($filterData) &&
            request()->get('pg', 1) == 1;
    }
}
