<?php

namespace App\Portal\Services;

use App\Events\SearchTriggered;
use App\Services\GroupSearchRepository;
use Framework\Event\EventDisptatcher;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

class SearchGroupService
{

    private GroupSearchRepository $groupRepo;

    private CrawlerDetect $crawlerDetect;

    public function __construct(GroupSearchRepository $groupRepo, CrawlerDetect $crawlerDetect)
    {
        $this->groupRepo = $groupRepo;
        $this->crawlerDetect = $crawlerDetect;
    }

    public function search($filter, int $perPage = 21)
    {
        $groups = $this->groupRepo->search($filter, $perPage);

        if (!$this->crawlerDetect->isCrawler()) {
            $this->logEvent($filter);
        }

        return $groups;
    }

    private function logEvent($data)
    {
        if (isset($data['status'])) {
            unset($data['status']);
        }

        if (isset($data['pending'])) {
            unset($data['pending']);
        }

        if (self::shouldLog($data)) {
            $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            EventDisptatcher::dispatch(new SearchTriggered('search', $data));
        }
    }

    private static function shouldLog($filterData): bool
    {
        return (bool) array_filter($filterData);
    }
}
